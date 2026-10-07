<?php

namespace App\Mcp\Tools;

use App\Models\WorkOrderPriority;
use App\Models\WorkOrderStatus;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('راهنمای فیلدها و مقادیر مجاز سیستم سفارش کار. وقتی مطمئن نیستی چه فیلتری داری، این رو صدا بزن.')]
class DescribeWorkOrdersTool extends Tool
{
    public function handle(Request $request): Response
    {
        $statuses = WorkOrderStatus::query()
            ->where('is_active', true)
            ->get(['key', 'label', 'is_final'])
            ->map(fn($s) => [
                'key'      => $s->key,
                'label'    => $s->label,
                'is_final' => $s->is_final,
            ])
            ->all();

        $priorities = WorkOrderPriority::query()
            ->where('is_active', true)
            ->ordered()
            ->get(['key', 'label', 'level'])
            ->map(fn($p) => [
                'key'   => $p->key,
                'label' => $p->label,
                'level' => $p->level,
            ])
            ->all();

        return Response::json([
            'statuses'    => $statuses,
            'priorities'  => $priorities,
            'date_format' => 'همه تاریخ‌ها YYYY-MM-DD میلادی (مثال: 2026-10-07)',
            'notes'       => [
                'برای «این ماه» از this_month استفاده کن (شمسی)',
                'برای «این هفته» از this_week استفاده کن',
                'برای «امروز» از today یا due_today استفاده کن',
                'برای شمارش از count_work_orders استفاده کن',
                'برای لیست از get_work_orders استفاده کن',
                'برای واحد از department_name استفاده کن (نه ID)',
            ],
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
