<?php

namespace App\Mcp\Tools;

use App\Models\Department;
use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('شمارش سفارش‌های کار با فیلترهای پیشرفته. برای سوالات «چند تا...» از این استفاده کن. مثال: چند سفارش بحرانی این ماه داریم؟')]
class CountWorkOrdersTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();
        $args = $request->all();

        $query = WorkOrder::query();

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

        $departmentId = null;
        if (! empty($args['department_id'])) {
            $departmentId = (int) $args['department_id'];
        } elseif (! empty($args['department_name'])) {
            $dept = Department::query()
                ->where('name', 'like', '%' . $args['department_name'] . '%')
                ->orWhere('code', 'like', '%' . $args['department_name'] . '%')
                ->first();

            if (! $dept) {
                return Response::json([
                    'count' => 0,
                    'error' => 'واحد با نام «' . $args['department_name'] . '» یافت نشد.',
                    'hint'  => 'از list_departments برای دیدن لیست واحدها استفاده کن.',
                ]);
            }
            $departmentId = $dept->id;
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if (! empty($args['statuses']) && is_array($args['statuses'])) {
            $query->whereHas('status', fn($s) => $s->whereIn('key', $args['statuses']));
        } elseif (! empty($args['status'])) {
            $query->whereHas('status', fn($s) => $s->where('key', $args['status']));
        }

        if (! empty($args['priorities']) && is_array($args['priorities'])) {
            $query->whereHas('priority', fn($p) => $p->whereIn('key', $args['priorities']));
        } elseif (! empty($args['priority'])) {
            $query->whereHas('priority', fn($p) => $p->where('key', $args['priority']));
        }
        if (isset($args['statuses']) && is_string($args['statuses'])) {
            $decoded = json_decode($args['statuses'], true);
            if (is_array($decoded)) {
                $args['statuses'] = $decoded;
            } else {
                $args['statuses'] = array_filter(array_map('trim', explode(',', $args['statuses'])));
            }
        }

        if (isset($args['priorities']) && is_string($args['priorities'])) {
            $decoded = json_decode($args['priorities'], true);
            if (is_array($decoded)) {
                $args['priorities'] = $decoded;
            } else {
                $args['priorities'] = array_filter(array_map('trim', explode(',', $args['priorities'])));
            }
        }

        if (! empty($args['assignee_username'])) {
            $query->whereHas('assignee', fn($a) => $a->where('username', $args['assignee_username']));
        }

        if (isset($args['is_open']) && $args['is_open']) {
            $query->whereHas('status', fn($s) => $s->where('is_final', false));
        }
        if (isset($args['is_final']) && $args['is_final']) {
            $query->whereHas('status', fn($s) => $s->where('is_final', true));
        }

        if (! empty($args['overdue_only'])) {
            $query->overdue();
        }
        if (! empty($args['due_today'])) {
            $query->whereDate('due_date', today());
        }

        if (! empty($args['due_from'])) {
            $query->whereDate('due_date', '>=', $args['due_from']);
        }
        if (! empty($args['due_to'])) {
            $query->whereDate('due_date', '<=', $args['due_to']);
        }

        if (! empty($args['created_from'])) {
            $query->whereDate('created_at', '>=', $args['created_from']);
        }
        if (! empty($args['created_to'])) {
            $query->whereDate('created_at', '<=', $args['created_to']);
        }

        if (! empty($args['completed_from'])) {
            $query->whereDate('completed_at', '>=', $args['completed_from']);
        }
        if (! empty($args['completed_to'])) {
            $query->whereDate('completed_at', '<=', $args['completed_to']);
        }

        if (! empty($args['this_month'])) {
            $query->whereBetween('created_at', [
                verta()->startMonth()->toCarbon(),
                verta()->endMonth()->toCarbon(),
            ]);
        }
        if (! empty($args['this_week'])) {
            $query->whereBetween('created_at', [
                verta()->startWeek()->toCarbon(),
                verta()->endWeek()->toCarbon(),
            ]);
        }
        if (! empty($args['today'])) {
            $query->whereDate('created_at', today());
        }

        $count = $query->count();

        $departmentName = $departmentId
            ? Department::query()->find($departmentId)?->name
            : null;

        return Response::json([
            'count'           => $count,
            'department_id'   => $departmentId,
            'department_name' => $departmentName,
            'filters'         => $this->describeFilters($args),
            'message'         => "بر اساس فیلترهای اعمال‌شده، {$count} سفارش کار یافت شد.",
        ]);
    }

    protected function describeFilters(array $args): array
    {
        $labels = [
            'status'            => 'وضعیت',
            'statuses'          => 'وضعیت‌ها',
            'priority'          => 'اولویت',
            'priorities'        => 'اولویت‌ها',
            'department_id'     => 'شناسه واحد',
            'department_name'   => 'نام واحد',
            'assignee_username' => 'مسئول',
            'is_open'           => 'باز',
            'is_final'          => 'نهایی',
            'overdue_only'      => 'معوق',
            'due_today'         => 'سررسید امروز',
            'due_from'          => 'سررسید از',
            'due_to'            => 'سررسید تا',
            'created_from'      => 'ایجاد از',
            'created_to'        => 'ایجاد تا',
            'completed_from'    => 'تکمیل از',
            'completed_to'      => 'تکمیل تا',
            'this_month'        => 'این ماه',
            'this_week'         => 'این هفته',
            'today'             => 'امروز',
        ];

        $out = [];
        foreach ($args as $key => $val) {
            if ($val === null || $val === '' || $val === false) continue;
            $out[$labels[$key] ?? $key] = $val;
        }
        return $out;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'department_name'   => ['type' => 'string', 'description' => 'نام واحد (مثال: انفورماتیک IT)'],
            'department_id'     => ['type' => 'integer', 'description' => 'شناسه عددی واحد'],
            'status'            => ['type' => 'string', 'description' => 'draft, pending, assigned, in_progress, completed, cancelled'],
            'statuses'          => ['type' => 'array', 'description' => 'چند وضعیت همزمان', 'items' => ['type' => 'string']],
            'priority'          => ['type' => 'string', 'description' => 'low, medium, high, critical'],
            'priorities'        => ['type' => 'array', 'description' => 'چند اولویت همزمان', 'items' => ['type' => 'string']],
            'assignee_username' => ['type' => 'string', 'description' => 'نام کاربری مسئول'],
            'is_open'           => ['type' => 'boolean', 'description' => 'فقط بازها'],
            'is_final'          => ['type' => 'boolean', 'description' => 'فقط نهایی‌ها'],
            'overdue_only'      => ['type' => 'boolean', 'description' => 'فقط معوق‌ها'],
            'due_today'         => ['type' => 'boolean', 'description' => 'سررسید امروز'],
            'due_from'          => ['type' => 'string', 'description' => 'Y-m-d'],
            'due_to'            => ['type' => 'string', 'description' => 'Y-m-d'],
            'created_from'      => ['type' => 'string', 'description' => 'Y-m-d'],
            'created_to'        => ['type' => 'string', 'description' => 'Y-m-d'],
            'completed_from'    => ['type' => 'string', 'description' => 'Y-m-d'],
            'completed_to'      => ['type' => 'string', 'description' => 'Y-m-d'],
            'this_month'        => ['type' => 'boolean', 'description' => 'این ماه شمسی'],
            'this_week'         => ['type' => 'boolean', 'description' => 'این هفته شمسی'],
            'today'             => ['type' => 'boolean', 'description' => 'امروز'],
        ];
    }
}
