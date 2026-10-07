<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class SafeQueryRunner
{
    protected array $allowedTables = [
        'work_orders',
        'work_order_statuses',
        'work_order_priorities',
        'work_order_status_histories',
        'work_order_checklist_items',
        'work_order_assignments',
        'departments',
        'users',
        'department_users',
    ];

    protected array $blockedColumns = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'api_token',
        'settings',
    ];

    protected int $maxRows = 100;

    protected int $timeout = 5;

    protected array $forbiddenKeywords = [
        'insert', 'update', 'delete', 'drop', 'alter', 'truncate',
        'create', 'replace', 'grant', 'revoke', 'exec', 'execute',
        'call', 'merge', 'into', 'load_file', 'outfile', 'dumpfile',
        'sleep', 'benchmark', 'information_schema',
    ];

    public function __construct(
        protected string $question = '',
        protected ?int $userId = null
    ) {}

    /**
     *
     * @return array{success: bool, rows: array, columns: array, count: int, error: ?string, sql: string}
     */
    public function run(string $sql): array
    {
        $startTime = microtime(true);

        $sql = trim(rtrim($sql, ';'));

        $error = $this->validate($sql);
        if ($error) {
            $this->log($sql, 0, $startTime, false, $error);
            return [
                'success' => false,
                'rows'    => [],
                'columns' => [],
                'count'   => 0,
                'error'   => $error,
                'sql'     => $sql,
            ];
        }

        $sql = $this->applyLimit($sql);

        try {
            DB::statement("SET SESSION MAX_EXECUTION_TIME=" . ($this->timeout * 1000));

            $results = DB::select($sql);

            $results = $this->sanitizeResults($results);

            if (count($results) > $this->maxRows) {
                $results = array_slice($results, 0, $this->maxRows);
            }

            $rows = array_map(fn($r) => (array) $r, $results);
            $columns = ! empty($rows) ? array_keys($rows[0]) : [];

            $this->log($sql, count($rows), $startTime, true, null);

            return [
                'success' => true,
                'rows'    => $rows,
                'columns' => $columns,
                'count'   => count($rows),
                'error'   => null,
                'sql'     => $sql,
            ];

        } catch (\Throwable $e) {
            $this->log($sql, 0, $startTime, false, $e->getMessage());

            return [
                'success' => false,
                'rows'    => [],
                'columns' => [],
                'count'   => 0,
                'error'   => 'خطا در اجرای کوئری: ' . $e->getMessage(),
                'sql'     => $sql,
            ];
        }
    }

    protected function validate(string $sql): ?string
    {
        if (! preg_match('/^\s*(SELECT|WITH)\b/i', $sql)) {
            return 'فقط کوئری SELECT مجاز است.';
        }

        $lower = strtolower($sql);

        foreach ($this->forbiddenKeywords as $keyword) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $sql)) {
                return "کلمه «{$keyword}» در کوئری مجاز نیست.";
            }
        }

        if (substr_count($sql, ';') > 0) {
            return 'اجرای چند کوئری همزمان مجاز نیست.';
        }

        if (preg_match('/(--|\/\*|#)/', $sql)) {
            return 'کامنت در کوئری مجاز نیست.';
        }

        $tables = $this->extractTables($sql);
        foreach ($tables as $table) {
            if (! in_array($table, $this->allowedTables, true)) {
                return "دسترسی به جدول «{$table}» مجاز نیست.";
            }
        }

        return null;
    }

    protected function extractTables(string $sql): array
    {
        $tables = [];

        // FROM/JOIN table_name
        if (preg_match_all('/(?:FROM|JOIN)\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?/i', $sql, $m)) {
            $tables = array_merge($tables, $m[1]);
        }

        return array_unique($tables);
    }

    protected function applyLimit(string $sql): string
    {
        if (! preg_match('/\bLIMIT\b/i', $sql)) {
            $sql .= ' LIMIT ' . $this->maxRows;
        } else {
            if (preg_match('/\bLIMIT\s+(\d+)/i', $sql, $m)) {
                $existing = (int) $m[1];
                if ($existing > $this->maxRows) {
                    $sql = preg_replace(
                        '/\bLIMIT\s+\d+/i',
                        'LIMIT ' . $this->maxRows,
                        $sql
                    );
                }
            }
        }

        return $sql;
    }

    protected function sanitizeResults(array $results): array
    {
        return array_map(function ($row) {
            $arr = (array) $row;
            foreach ($this->blockedColumns as $col) {
                if (array_key_exists($col, $arr)) {
                    $arr[$col] = '[REDACTED]';
                }
            }
            return (object) $arr;
        }, $results);
    }

    protected function log(string $sql, int $count, float $startTime, bool $success, ?string $error): void
    {
        try {
            \App\Models\AiQueryLog::query()->create([
                'user_id'     => $this->userId ?? auth()->id(),
                'question'    => $this->question,
                'sql'         => $sql,
                'row_count'   => $count,
                'duration_ms' => (int) ((microtime(true) - $startTime) * 1000),
                'is_success'  => $success,
                'error'       => $error,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AiQueryLog failed', ['err' => $e->getMessage()]);
        }
    }
}
