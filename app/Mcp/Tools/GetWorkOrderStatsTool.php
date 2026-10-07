<?php

namespace App\Mcp\Tools;

use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن آمار کلی سفارش‌های کار: تعداد باز، در حال انجام، تکمیل شده، معوق و سررسید امروز. همیشه اول این را صدا بزن وقتی کاربر سوال کلی می‌پرسد.')]
class GetWorkOrderStatsTool extends Tool
{
    /**
     * Handle the tool request.
     * @throws \JsonException
     */
    public function handle(Request $request): Response
    {
        $user = auth()->user();

        $base = fn() => WorkOrder::query()
            ->when($user?->role === 'manager', function ($q) use ($user) {
                $deptIds = $user->managedDepartments()->pluck('departments.id');
                $q->where(function ($qq) use ($deptIds, $user) {
                    $qq->whereIn('department_id', $deptIds)
                        ->orWhere('created_by', $user->id)
                        ->orWhere('assignee_id', $user->id);
                });
            })
            ->when($user?->role === 'user', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->where('assignee_id', $user->id)
                        ->orWhere('created_by', $user->id);
                });
            });

        $open = $base()->whereHas('status', fn($s) => $s->where('is_final', false))->count();

        $inProgress = $base()->whereHas('status', fn($s) => $s->where('key', 'in_progress'))->count();

        $completedThisMonth = $base()
            ->whereHas('status', fn($s) => $s->where('key', 'completed'))
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();

        $overdue = $base()
            ->whereHas('status', fn($s) => $s->where('is_final', false))
            ->whereDate('due_date', '<', today())
            ->count();

        $dueToday = $base()
            ->whereHas('status', fn($s) => $s->where('is_final', false))
            ->whereDate('due_date', today())
            ->count();

        return Response::json([
            'open'                 => $open,
            'in_progress'          => $inProgress,
            'completed_this_month' => $completedThisMonth,
            'overdue'              => $overdue,
            'due_today'            => $dueToday,
            'total'                => $open + $completedThisMonth,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
