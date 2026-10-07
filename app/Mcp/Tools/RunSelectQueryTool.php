<?php

namespace App\Mcp\Tools;

use App\Services\Ai\SafeQueryRunner;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('اجرای کوئری SELECT امن روی دیتابیس سیستم سفارش کار. برای سوالات پیچیده که با ابزارهای دیگه جواب نمی‌گیرن. فقط SELECT مجاز است — INSERT/UPDATE/DELETE/DROP ممنوع. مثال: SELECT status_id, COUNT(*) FROM work_orders GROUP BY status_id')]
class RunSelectQueryTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();

        if ($user?->role !== 'admin') {
            return Response::json([
                'success' => false,
                'error'   => 'اجرای کوئری خام فقط برای ادمین‌ها مجاز است.',
            ]);
        }

        $args = $request->all();
        $sql  = trim($args['sql'] ?? '');

        if ($sql === '') {
            return Response::json([
                'success' => false,
                'error'   => 'کوئری خالی است.',
            ]);
        }

        $question = $args['_question'] ?? $args['question'] ?? '';

        $runner = new \App\Services\Ai\SafeQueryRunner($question, $user->id);
        $result = $runner->run($sql);

        if (! $result['success']) {
            return Response::json([
                'success' => false,
                'error'   => $result['error'],
            ]);
        }

        $rows = $result['rows'];
        $count = count($rows);

        if ($count === 1 && count($rows[0]) === 1) {
            $value = array_values($rows[0])[0];

            return Response::json([
                'success' => true,
                'answer'  => $value,
                'hint'    => "پاسخ {$value} است.",
                'rows'    => $rows,
                'count'   => 1,
            ]);
        }

        return Response::json([
            'success' => true,
            'count'   => $count,
            'columns' => $result['columns'],
            'rows'    => $rows,
            'hint'    => "{$count} ردیف داده بازگشت.",
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'sql' => [
                'type'        => 'string',
                'description' => 'کوئری SELECT معتبر. فقط SELECT مجاز است. مثال: SELECT id, code, title FROM work_orders WHERE department_id = 6 LIMIT 10',
            ],
            'question' => [
                'type'        => 'string',
                'description' => 'سوال اصلی کاربر (برای لاگ)',
            ],
        ];
    }
}
