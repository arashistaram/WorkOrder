<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن ساختار جدول‌های دیتابیس (ستون‌ها و نوع‌شان). قبل از نوشتن SQL خام، اگه مطمئن نیستی ستون‌ها چی هستن، این رو صدا بزن.')]
class DescribeDatabaseSchemaTool extends Tool
{

    protected array $allowedTables = [
        'work_orders',
        'work_order_statuses',
        'work_order_priorities',
        'work_order_status_histories',
        'work_order_checklist_items',
        'work_order_assignments',
        'work_order_attachments',
        'departments',
        'users',
        'department_users',
    ];

    public function handle(Request $request): Response
    {
        $args = $request->all();
        $tableName = $args['table'] ?? null;

        if ($tableName) {
            if (! in_array($tableName, $this->allowedTables, true)) {
                return Response::json([
                    'error' => "جدول «{$tableName}» مجاز نیست.",
                    'allowed_tables' => $this->allowedTables,
                ]);
            }

            $columns = $this->getTableColumns($tableName);

            return Response::json([
                'table'   => $tableName,
                'columns' => $columns,
            ]);
        }

        $schema = [];
        foreach ($this->allowedTables as $table) {
            $schema[$table] = $this->getTableColumns($table);
        }

        return Response::json([
            'tables' => $schema,
            'note'   => 'برای اطلاعات کامل‌تر یک جدول، با پارامتر table صدا بزن.',
        ]);
    }

    protected function getTableColumns(string $table): array
    {
        try {
            $columns = DB::select("SHOW COLUMNS FROM `{$table}`");

            return array_map(function ($col) {
                return [
                    'name'     => $col->Field,
                    'type'     => $col->Type,
                    'nullable' => $col->Null === 'YES',
                    'key'      => $col->Key ?: null,
                ];
            }, $columns);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'table' => [
                'type'        => 'string',
                'description' => 'نام جدول (اختیاری). اگه خالی باشه، همه جدول‌ها برمیگرده.',
            ],
        ];
    }
}
