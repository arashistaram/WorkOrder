<?php

namespace App\Mcp\Tools;

use App\Models\Department;
use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن لیست سفارش‌های کار با فیلترهای پیشرفته. برای سوالات «کدام‌ها...» یا «نشون بده» از این استفاده کن.')]
class GetWorkOrdersTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();
        $args = $request->all();

        $limit = min((int) ($args['limit'] ?? 10), 50);

        $query = WorkOrder::query()->with([
            'department:id,name',
            'assignee:id,name,username',
            'status:id,label,key,color',
            'priority:id,label,key,color',
        ]);

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
            if ($dept) $departmentId = $dept->id;
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

        $sortBy  = $args['sort_by'] ?? 'id';
        $sortDir = ($args['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['id', 'created_at', 'due_date', 'priority_id'];
        if (! in_array($sortBy, $allowedSorts, true)) $sortBy = 'id';

        $rows = $query->orderBy($sortBy, $sortDir)->limit($limit)->get();

        return Response::json([
            'count' => $rows->count(),
            'items' => $rows->map(fn($wo) => [
                'id'           => $wo->id,
                'code'         => $wo->code,
                'title'        => $wo->title,
                'status'       => $wo->status?->label,
                'status_key'   => $wo->status?->key,
                'priority'     => $wo->priority?->label,
                'priority_key' => $wo->priority?->key,
                'department'   => $wo->department?->name,
                'assignee'     => $wo->assignee?->name,
                'due_date'     => $wo->due_date?->format('Y-m-d'),
                'is_overdue'   => $wo->is_overdue,
                'progress'     => $wo->progress,
                'url'          => route('work-orders.detail', $wo->id),
            ])->all(),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'department_name' => ['type' => 'string', 'description' => 'نام واحد (مثال: انفورماتیک)'],
            'department_id'   => ['type' => 'integer', 'description' => 'شناسه عددی واحد'],
            'status'          => ['type' => 'string', 'description' => 'وضعیت تکی: draft, pending, assigned, in_progress, completed, cancelled'],
            'statuses'        => ['type' => 'array', 'description' => 'چند وضعیت همزمان', 'items' => ['type' => 'string']],
            'priority'        => ['type' => 'string', 'description' => 'اولویت تکی: low, medium, high, critical'],
            'priorities'      => ['type' => 'array', 'description' => 'چند اولویت همزمان', 'items' => ['type' => 'string']],
            'assignee_username' => ['type' => 'string', 'description' => 'نام کاربری مسئول'],
            'is_open'         => ['type' => 'boolean', 'description' => 'فقط بازها'],
            'is_final'        => ['type' => 'boolean', 'description' => 'فقط نهایی‌ها'],
            'overdue_only'    => ['type' => 'boolean', 'description' => 'فقط معوق‌ها'],
            'due_today'       => ['type' => 'boolean', 'description' => 'سررسید امروز'],
            'due_from'        => ['type' => 'string', 'description' => 'سررسید از (Y-m-d)'],
            'due_to'          => ['type' => 'string', 'description' => 'سررسید تا (Y-m-d)'],
            'created_from'    => ['type' => 'string', 'description' => 'ایجاد از (Y-m-d)'],
            'created_to'      => ['type' => 'string', 'description' => 'ایجاد تا (Y-m-d)'],
            'this_month'      => ['type' => 'boolean', 'description' => 'این ماه شمسی'],
            'this_week'       => ['type' => 'boolean', 'description' => 'این هفته شمسی'],
            'today'           => ['type' => 'boolean', 'description' => 'امروز'],
            'sort_by'         => ['type' => 'string', 'description' => 'id, created_at, due_date, priority_id'],
            'sort_dir'        => ['type' => 'string', 'description' => 'asc یا desc'],
            'limit'           => ['type' => 'integer', 'description' => 'حداکثر (پیش‌فرض 10، حداکثر 50)'],
        ];
    }
}
