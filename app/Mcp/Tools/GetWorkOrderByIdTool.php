<?php

namespace App\Mcp\Tools;

use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('گرفتن اطلاعات کامل یک سفارش کار با کد (مثل WO-1404-0012) یا ID عددی')]
class GetWorkOrderByIdTool extends Tool
{
    public function handle(Request $request): Response
    {
        $args = $request->all();
        $identifier = trim((string) ($args['identifier'] ?? $args['id'] ?? ''));

        if ($identifier === '') {
            return Response::json(['error' => 'شناسه سفارش خالی است.']);
        }

        $wo = is_numeric($identifier)
            ? WorkOrder::query()->find($identifier)
            : WorkOrder::query()->where('code', $identifier)->first();

        if (! $wo) {
            return Response::json(['error' => "سفارش با شناسه {$identifier} یافت نشد."]);
        }

        $user = auth()->user();
        if ($user && ! $user->can('view', $wo)) {
            return Response::json(['error' => 'شما به این سفارش دسترسی ندارید.']);
        }

        $wo->load([
            'department:id,name',
            'assignee:id,name,username',
            'creator:id,name',
            'status:id,label,key,color',
            'priority:id,label,key,color',
            'checklistItems:id,work_order_id,title,is_done,sort_order',
            'statusHistories:id,work_order_id,note,created_at',
        ]);

        return Response::json([
            'id'          => $wo->id,
            'code'        => $wo->code,
            'title'       => $wo->title,
            'description' => $wo->description,
            'status'      => $wo->status?->label,
            'priority'    => $wo->priority?->label,
            'department'  => $wo->department?->name,
            'assignee'    => $wo->assignee?->name,
            'creator'     => $wo->creator?->name,
            'due_date'    => $wo->due_date?->format('Y-m-d'),
            'is_overdue'  => $wo->is_overdue,
            'progress'    => $wo->progress,
            'checklist'   => $wo->checklistItems->map(fn($i) => [
                'title'   => $i->title,
                'is_done' => $i->is_done,
            ])->all(),
            'history'     => $wo->statusHistories->take(5)->map(fn($h) => [
                'note' => $h->note,
                'at'   => $h->created_at?->format('Y-m-d H:i'),
            ])->all(),
            'url'         => route('work-orders.detail', $wo->id),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'identifier' => [
                'type'        => 'string',
                'description' => 'کد سفارش (مثل WO-1404-0012) یا ID عددی',
            ],
        ];
    }
}
