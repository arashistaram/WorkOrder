<?php

namespace App\Mcp\Tools;

use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن لیست سفارش‌های کار معوق (سررسید گذشته و هنوز باز)')]
class GetOverdueWorkOrdersTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();

        $query = WorkOrder::query()
            ->with(['department:id,name', 'assignee:id,name', 'priority:id,label,key,color'])
            ->overdue()
            ->orderBy('due_date');

        if ($user?->role === 'manager') {
            $deptIds = $user->managedDepartments()->pluck('departments.id');
            $query->where(function ($qq) use ($deptIds, $user) {
                $qq->whereIn('department_id', $deptIds)
                    ->orWhere('created_by', $user->id)
                    ->orWhere('assignee_id', $user->id);
            });
        } elseif ($user?->role === 'user') {
            $query->where('assignee_id', $user->id);
        }

        $rows = $query->limit(20)->get();

        return Response::json([
            'count' => $rows->count(),
            'items' => $rows->map(fn($wo) => [
                'code'         => $wo->code,
                'title'        => $wo->title,
                'department'   => $wo->department?->name,
                'assignee'     => $wo->assignee?->name,
                'priority'     => $wo->priority?->label,
                'due_date'     => $wo->due_date?->format('Y-m-d'),
                'days_overdue' => $wo->due_date ? (int) $wo->due_date->diffInDays(today()) : null,
                'url'          => route('work-orders.detail', $wo->id),
            ])->all(),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
