<?php

namespace App\Mcp\Tools;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن بار کاری هر مسئول: تعداد سفارش‌های باز به تفکیک کاربر')]
class GetWorkloadTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $rows = WorkOrder::query()
            ->select('assignee_id', DB::raw('COUNT(*) as open_count'))
            ->whereHas('status', fn($s) => $s->where('is_final', false))
            ->whereNotNull('assignee_id')
            ->groupBy('assignee_id')
            ->orderByDesc('open_count')
            ->limit(15)
            ->get();

        $users = User::query()->whereIn('id', $rows->pluck('assignee_id'))
            ->pluck('name', 'id');

        return Response::json([
            'items' => $rows->map(fn($r) => [
                'name'  => $users[$r->assignee_id] ?? '—',
                'count' => (int) $r->open_count,
            ])->all(),
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            //
        ];
    }
}
