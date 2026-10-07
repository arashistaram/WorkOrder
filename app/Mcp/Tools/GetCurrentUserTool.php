<?php

namespace App\Mcp\Tools;

use App\Models\WorkOrder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('اطلاعات کاربر جاری: نام، نام کاربری، نقش، واحدها، شماره تماس. وقتی کاربر از «من» یا «پروفایل من» سوال پرسید از این استفاده کن.')]
class GetCurrentUserTool extends Tool
{
    public function handle(Request $request): Response
    {
        $user = auth()->user();

        if (! $user) {
            return Response::json(['error' => 'کاربری لاگین نکرده.']);
        }

        $user->load(['departments:id,name,code']);

        $assignedCount = WorkOrder::query()
            ->where('assignee_id', $user->id)
            ->whereHas('status', fn($s) => $s->where('is_final', false))
            ->count();

        $createdCount = WorkOrder::query()
            ->where('created_by', $user->id)
            ->whereHas('status', fn($s) => $s->where('is_final', false))
            ->count();

        return Response::json([
            'id'            => $user->id,
            'name'          => $user->name,
            'username'      => $user->username,
            'role'          => $user->role,
            'role_label'    => match ($user->role) {
                'admin'   => 'مدیر سیستم',
                'manager' => 'مدیر',
                'user'    => 'کاربر عادی',
                default   => $user->role,
            },
            'phone'         => $user->phone,
            'email'         => $user->email,
            'job_title'     => $user->job_title,
            'employee_code' => $user->employee_code,
            'departments'   => $user->departments->map(fn($d) => [
                'id'   => $d->id,
                'name' => $d->name,
                'code' => $d->code,
            ])->all(),
            'open_assigned' => $assignedCount,
            'open_created'  => $createdCount,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
