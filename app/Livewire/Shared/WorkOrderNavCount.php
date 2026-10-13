<?php

namespace App\Livewire\Shared;

use App\Models\WorkOrder;
use Faker\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkOrderNavCount extends Component
{
    #[Computed]
    public function count(): int
    {
        $user = auth()->user();
        if (! $user) return 0;

        return match ($user->role) {
            'admin'   => $this->adminCount($user),
            'manager' => $this->managerCount($user),
            'user'    => $this->userCount($user),
            default   => 0,
        };
    }

    protected function adminCount($user): int
    {
        return WorkOrder::query()
            ->where('approval_stage', WorkOrder::STAGE_PENDING_ADMIN)
            ->count();
    }

    protected function managerCount($user): int
    {
        $deptIds = $user->effectiveManagedDepartmentIds()
            ->merge($user->effectiveSupervisedDepartmentIds())
            ->unique();

        $pendingStatusId = \App\Models\WorkOrderStatus::query()
            ->where('key', 'pending')
            ->value('id');

        return WorkOrder::query()
            ->where(function ($q) use ($user, $deptIds, $pendingStatusId) {
                if ($pendingStatusId && $deptIds->isNotEmpty()) {
                    $q->orWhere(function ($qq) use ($pendingStatusId, $deptIds) {
                        $qq->where('status_id', $pendingStatusId)
                            ->whereIn('department_id', $deptIds);
                    });
                }

                $q->orWhere(function ($qq) use ($user, $deptIds) {
                    $qq->whereHas('status', fn($s) => $s->where('is_final', false))
                        ->where(function ($inner) use ($user, $deptIds) {

                            if ($deptIds->isNotEmpty()) {
                                $inner->orWhere(function ($i) use ($deptIds) {
                                    $i->where('approval_stage', WorkOrder::STAGE_PENDING_MANAGER)
                                        ->whereIn('department_id', $deptIds);
                                });
                            }

                            $inner->orWhere(function ($i) use ($user) {
                                $i->where('manager_approved_by', $user->id)
                                    ->where('approval_stage', WorkOrder::STAGE_APPROVED)
                                    ->whereNull('assignee_id');
                            });

                            $inner->orWhere('assignee_id', $user->id);

                            $inner->orWhere('created_by', $user->id);
                        });
                });
            })
            ->count();
    }

    protected function userCount($user): int
    {
        return WorkOrder::query()
            ->where(function ($q) use ($user) {

                $q->orWhere(function ($qq) use ($user) {
                    $qq->where('assignee_id', $user->id)
                        ->whereHas('status', fn($s) => $s->where('is_final', false));
                });

                $q->orWhere(function ($qq) use ($user) {
                    $qq->where('created_by', $user->id)
                        ->whereHas('status', fn($s) => $s->where('is_final', false));
                });
            })
            ->count();
    }

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.shared.work-order-nav-count');
    }
}
