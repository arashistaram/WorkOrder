<?php

namespace App\Mcp\Tools;

use App\Models\Department;
use App\Models\WorkOrder;
use App\Models\WorkOrderPriority;
use App\Models\WorkOrderStatus;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('ایجاد یک سفارش کار جدید. فقط بعد از تأیید کاربر اجرا کن.')]
class CreateWorkOrderTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $user = auth()->user();
        $args = $request->all();

        if (! $user || ! Gate::forUser($user)->allows('create', WorkOrder::class)) {
            return Response::json(['error' => 'شما اجازه ایجاد سفارش کار را ندارید.']);
        }

        if (empty($args['title'])) {
            return Response::json(['error' => 'عنوان الزامی است.']);
        }

        $departmentId = null;
        if (! empty($args['department_id'])) {
            $departmentId = (int) $args['department_id'];
        } elseif (! empty($args['department_name'])) {
            $dept = Department::query()
                ->where('name', 'like', '%' . $args['department_name'] . '%')
                ->first();
            if ($dept) $departmentId = $dept->id;
        }

        if (! $departmentId) {
            return Response::json([
                'error' => 'واحد یافت نشد.',
                'hint'  => 'از list_departments برای دیدن واحدها استفاده کن.',
            ]);
        }

        $priorityKey = $args['priority_key'] ?? 'medium';
        $priorityId = WorkOrderPriority::query()->where('key', $priorityKey)->value('id')
            ?? WorkOrderPriority::query()->where('is_active', true)->value('id');

        $statusId = WorkOrderStatus::query()->where('key', 'pending')->value('id')
            ?? WorkOrderStatus::query()->where('is_active', true)->value('id');

        $wo = WorkOrder::query()->create([
            'code'          => WorkOrder::generateCode(),
            'title'         => $args['title'],
            'description'   => $args['description'] ?? null,
            'department_id' => $departmentId,
            'priority_id'   => $priorityId,
            'status_id'     => $statusId,
            'created_by'    => $user->id,
            'due_date'      => $args['due_date'] ?? null,
        ]);

        $wo->statusHistories()->create([
            'from_status_id' => null,
            'to_status_id'   => $statusId,
            'changed_by'     => $user->id,
            'note'           => 'ایجاد از طریق دستیار هوشمند',
            'created_at'     => now(),
        ]);

        return Response::json([
            'success' => true,
            'code'    => $wo->code,
            'id'      => $wo->id,
            'url'     => route('work-orders.detail', $wo->id),
            'message' => "سفارش کار {$wo->code} با موفقیت ایجاد شد.",
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title'           => ['type' => 'string', 'description' => 'عنوان سفارش'],
            'description'     => ['type' => 'string', 'description' => 'توضیحات'],
            'department_name' => ['type' => 'string', 'description' => 'نام واحد (ترجیحاً)'],
            'department_id'   => ['type' => 'integer', 'description' => 'شناسه واحد'],
            'priority_key'    => ['type' => 'string', 'description' => 'low, medium, high, critical'],
            'due_date'        => ['type' => 'string', 'description' => 'سررسید Y-m-d'],
        ];
    }
}
