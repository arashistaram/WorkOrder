<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گروه‌بندی سفارش‌های کار بر اساس یک فیلد مشخص (وضعیت، اولویت، واحد، مسئول). برای سوالات «به تفکیک...» یا «بر اساس...» از این استفاده کن.')]
class GroupWorkOrdersTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();
        $args = $request->all();

        $groupBy = $args['group_by'] ?? 'status';
        $allowed = ['status', 'priority', 'department', 'assignee', 'month'];

        if (! in_array($groupBy, $allowed, true)) {
            return Response::json([
                'error' => "گروه‌بندی با «{$groupBy}» مجاز نیست.",
                'allowed' => $allowed,
            ]);
        }

        $query = WorkOrder::query();

        // فیلتر نقش کاربر
        if ($user?->role === 'manager') {
            $deptIds = $user->managedDepartments()->pluck('departments.id');
            $query->where(function ($qq) use ($deptIds, $user) {
                $qq->whereIn('department_id', $deptIds)
                    ->orWhere('created_by', $user->id)
                    ->orWhere('assignee_id', $user->id);
            });
        } elseif ($user?->role === 'user') {
            $query->where(function ($qq) use ($user) {
                $qq->where('assignee_id', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        // فیلترهای اضافی
        if (! empty($args['department_name'])) {
            $dept = Department::query()
                ->where('name', 'like', '%' . $args['department_name'] . '%')
                ->first();
            if ($dept) $query->where('department_id', $dept->id);
        }

        if (! empty($args['this_month'])) {
            $query->whereBetween('created_at', [
                verta()->startMonth()->toCarbon(),
                verta()->endMonth()->toCarbon(),
            ]);
        }

        if (! empty($args['is_open'])) {
            $query->whereHas('status', fn($s) => $s->where('is_final', false));
        }

        if (! empty($args['overdue_only'])) {
            $query->overdue();
        }

        // ساخت گروه‌بندی
        $result = match ($groupBy) {
            'status'     => $this->groupByStatus($query),
            'priority'   => $this->groupByPriority($query),
            'department' => $this->groupByDepartment($query),
            'assignee'   => $this->groupByAssignee($query),
            'month'      => $this->groupByMonth($query),
        };

        return Response::json([
            'group_by' => $groupBy,
            'groups'   => $result,
            'total'    => collect($result)->sum('count'),
        ]);
    }

    protected function groupByStatus($query): array
    {
        $rows = (clone $query)
            ->select('status_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('status_id')
            ->groupBy('status_id')
            ->get();

        $statuses = \App\Models\WorkOrderStatus::query()
            ->whereIn('id', $rows->pluck('status_id'))
            ->pluck('label', 'id');

        return $rows->map(fn($r) => [
            'key'   => $r->status_id,
            'label' => $statuses[$r->status_id] ?? '—',
            'count' => (int) $r->total,
        ])->sortByDesc('count')->values()->all();
    }

    protected function groupByPriority($query): array
    {
        $rows = (clone $query)
            ->select('priority_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('priority_id')
            ->groupBy('priority_id')
            ->get();

        $priorities = \App\Models\WorkOrderPriority::query()
            ->whereIn('id', $rows->pluck('priority_id'))
            ->pluck('label', 'id');

        return $rows->map(fn($r) => [
            'key'   => $r->priority_id,
            'label' => $priorities[$r->priority_id] ?? '—',
            'count' => (int) $r->total,
        ])->sortByDesc('count')->values()->all();
    }

    protected function groupByDepartment($query): array
    {
        $rows = (clone $query)
            ->select('department_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->get();

        $depts = Department::query()
            ->whereIn('id', $rows->pluck('department_id'))
            ->pluck('name', 'id');

        return $rows->map(fn($r) => [
            'key'   => $r->department_id,
            'label' => $depts[$r->department_id] ?? '—',
            'count' => (int) $r->total,
        ])->sortByDesc('count')->values()->all();
    }

    protected function groupByAssignee($query): array
    {
        $rows = (clone $query)
            ->select('assignee_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('assignee_id')
            ->groupBy('assignee_id')
            ->get();

        $users = \App\Models\User::query()
            ->whereIn('id', $rows->pluck('assignee_id'))
            ->pluck('name', 'id');

        return $rows->map(fn($r) => [
            'key'   => $r->assignee_id,
            'label' => $users[$r->assignee_id] ?? '—',
            'count' => (int) $r->total,
        ])->sortByDesc('count')->values()->all();
    }

    protected function groupByMonth($query): array
    {
        $rows = (clone $query)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        return $rows->map(fn($r) => [
            'key'   => "{$r->year}-{$r->month}",
            'label' => verta("{$r->year}-{$r->month}-01")->format('%B %Y'),
            'count' => (int) $r->total,
        ])->all();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'group_by' => [
                'type'        => 'string',
                'description' => 'مبنای گروه‌بندی: status (وضعیت), priority (اولویت), department (واحد), assignee (مسئول), month (ماه)',
            ],
            'department_name' => [
                'type'        => 'string',
                'description' => 'فیلتر واحد (اختیاری)',
            ],
            'this_month' => [
                'type'        => 'boolean',
                'description' => 'فقط این ماه',
            ],
            'is_open' => [
                'type'        => 'boolean',
                'description' => 'فقط بازها',
            ],
            'overdue_only' => [
                'type'        => 'boolean',
                'description' => 'فقط معوق‌ها',
            ],
        ];
    }
}
