<?php

namespace App\Livewire\Dashboard\WorkOrder;

use App\Models\Department;
use App\Models\WorkOrder;
use App\Models\WorkOrderStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts._dashboard')]
#[Title('تایید سفارش‌های کار')]
class WorkOrderApprovalController extends Component
{
    use WithPagination;

    public string $search = '';
    public string $tab    = 'manager'; // manager | admin
    public bool $showRejectModal = false;
    public ?int $rejectingId = null;
    public string $rejectionReason = '';

    public bool $showApproveModal = false;
    public ?int $approvingId      = null;
    public ?int $approveToDepartmentId = null;
    public bool $needsAdminApproval    = false;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->isAdmin()
            && $user->effectiveManagedDepartmentIds()->isEmpty()
            && $user->effectiveSupervisedDepartmentIds()->isEmpty()) {
            $this->tab = 'admin';
        }
    }

    public function switchTab(string $tab): void
    {
        $this->tab = in_array($tab, ['manager', 'admin'], true) ? $tab : 'manager';
        $this->resetPage();
        unset($this->pendingOrders);
    }

    #[Computed]
    public function pendingOrders(): LengthAwarePaginator
    {
        $user = auth()->user();

        return WorkOrder::query()
            ->with(['department', 'creator', 'priority', 'managerApprovedBy'])
            ->when($this->tab === 'manager', function ($q) use ($user) {
                $deptIds = $user->effectiveManagedDepartmentIds()
                    ->merge($user->effectiveSupervisedDepartmentIds())
                    ->unique();

                $q->where('approval_stage', WorkOrder::STAGE_PENDING_MANAGER)
                    ->where(function ($qq) use ($deptIds, $user) {
                        // ۱) WOهایی که در واحدهای تحت مدیریت/سرپرستی کاربرند
                        if ($deptIds->isNotEmpty()) {
                            $qq->whereIn('department_id', $deptIds);
                        }

                        // ۲) WOهایی که خود کاربر ساخته (پیگیری)
                        $qq->orWhere('created_by', $user->id);
                    });
            })
            ->when($this->tab === 'admin', function ($q) use ($user) {
                if (! $user->isAdmin()) {
                    $q->whereRaw('1=0');
                    return;
                }
                $q->where('approval_stage', WorkOrder::STAGE_PENDING_ADMIN);
            })
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(fn($qq) => $qq
                    ->where('code', 'like', $s)
                    ->orWhere('title', 'like', $s)
                    ->orWhere('description', 'like', $s)
                );
            })
            ->orderByDesc('created_at')
            ->paginate(15);
    }


    #[Computed]
    public function managerDepartments()
    {
        $user = auth()->user();

        if (in_array($user->role, ['manager', 'admin']) || $user->isPrivileged() === true) {
            return \App\Models\Department::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

//        return $user->managedDepartments()
//            ->orderBy('departments.name')
//            ->get(['departments.id', 'departments.name']);
    }

//    #[Computed]
//    public function approvableDepartments(): Collection|\Illuminate\Support\Collection
//    {
//        $user = auth()->user();
//
//        if ($user->role === 'admin') {
//            return \App\Models\Department::query()
//                ->where('is_active', true)
//                ->orderBy('name')
//                ->get(['id', 'name']);
//        }
//
//        $ids = $user->effectiveSupervisedDepartmentIds()
//            ->merge($user->effectiveManagedDepartmentIds())
//            ->unique()
//            ->values();
//
//        if ($ids->isEmpty()) {
//            return collect();
//        }
//
//        return \App\Models\Department::query()
//            ->whereIn('id', $ids)
//            ->where('is_active', true)
//            ->orderBy('name')
//            ->get(['id', 'name']);
//    }

    #[Computed]
    public function substitutedDeptIds()
    {
        $user = auth()->user();

        return $user->substitutedSupervisedDepartments()->pluck('id')
            ->merge($user->substitutedManagedDepartments()->pluck('id'))
            ->unique()
            ->values()
            ->all();
    }

    public function openApproveModal(int $id): void
    {
        $wo = WorkOrder::query()->findOrFail($id);
        Gate::authorize('approve', $wo);

        $this->approvingId            = $id;
        $this->approveToDepartmentId  = $wo->department_id;
        $this->needsAdminApproval    = false;
        $this->showApproveModal       = true;
    }

    public function closeApproveModal(): void
    {
        $this->showApproveModal      = false;
        $this->approvingId           = null;
        $this->approveToDepartmentId = null;
        $this->needsAdminApproval    = false;
        $this->resetValidation();
    }

    public function approveAsManager(): void
    {
        $this->validate([
            'approveToDepartmentId' => 'required|exists:departments,id',
            'needsAdminApproval'    => 'boolean',
        ], [
            'approveToDepartmentId.required' => 'واحد مقصد را انتخاب کنید.',
        ]);

        $wo = WorkOrder::query()->findOrFail($this->approvingId);
        Gate::authorize('approveAsManager', $wo);

        $oldDeptId = $wo->department_id;
        $newDeptId = $this->approveToDepartmentId;
        $toAdmin   = $this->needsAdminApproval;

        DB::transaction(function () use ($wo, $oldDeptId, $newDeptId, $toAdmin) {
            $user = auth()->user();

            if ($toAdmin) {
                $nextStage  = WorkOrder::STAGE_PENDING_ADMIN;
                $nextStatus = WorkOrderStatus::query()->where('key', 'pending')->value('id')
                    ?? $wo->status_id;
                $note       = 'تایید مدیر واحد — ارجاع به مدیرعامل';
            } else {
                $nextStage  = WorkOrder::STAGE_APPROVED;
                $nextStatus = WorkOrderStatus::query()->where('key', 'pending')->value('id')
                    ?? WorkOrderStatus::query()->where('key', 'pending')->value('id')
                    ?? $wo->status_id;
                $note       = 'تایید نهایی مدیر واحد — آماده تخصیص';
            }

            if ($oldDeptId !== $newDeptId) {
                $oldName = Department::query()->find($oldDeptId)?->name;
                $newName = Department::query()->find($newDeptId)?->name;
                $note .= " • انتقال از «{$oldName}» به «{$newName}»";
            }

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $nextStatus,
                'changed_by'     => $user->id,
                'note'           => $note,
                'created_at'     => now(),
            ]);

            $updates = [
                'approval_stage'        => $nextStage,
                'needs_admin_approval'  => $toAdmin,
                'manager_approved_by'   => $user->id,
                'manager_approved_at'   => now(),
                'department_id'         => $newDeptId,
                'status_id'             => $nextStatus,
            ];

            if (! $toAdmin) {
                $updates['approval_status'] = 1;
                $updates['approved_by']     = $user->id;
                $updates['approved_at']     = now();
            }

            $wo->update($updates);
        });

        $this->closeApproveModal();
        unset($this->pendingOrders);

        $msg = $toAdmin
            ? 'سفارش برای تایید نهایی به مدیرعامل ارجاع شد.'
            : 'سفارش تایید نهایی شد و آماده تخصیص است.';

        LivewireAlert::title('انجام شد')->text($msg)
            ->success()->timer(5000)->show();
    }

    /**
     * @throws \Throwable
     */
    public function approve(): void
    {
        $this->validate([
            'approveToDepartmentId' => 'required|exists:departments,id',
        ], [
            'approveToDepartmentId.required' => 'واحد مقصد را انتخاب کنید.',
        ]);

        $wo = WorkOrder::query()->findOrFail($this->approvingId);
        Gate::authorize('approve', $wo);

        $oldDeptId = $wo->department_id;
        $newDeptId = $this->approveToDepartmentId;

        DB::transaction(function () use ($wo, $oldDeptId, $newDeptId) {
            $user = auth()->user();

            $pendingStatusId = WorkOrderStatus::query()
                ->where('key', 'pending')->value('id')
                ?? WorkOrderStatus::query()->active()->value('id');

            $note = $user->role == 'admin' ? ' تایید توسط مدیرعامل' :  'تایید توسط مدیر';

            if ($oldDeptId !== $newDeptId) {
                $oldName = \App\Models\Department::query()->find($oldDeptId)?->name;
                $newName = \App\Models\Department::query()->find($newDeptId)?->name;
                $note .= " • انتقال از «{$oldName}» به «{$newName}»";
            }

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $pendingStatusId,
                'changed_by'     => $user->id,
                'note'           => $note,
                'created_at'     => now(),
            ]);

            $wo->update([
                'approval_status' => 1,
                'approved_by'     => $user->id,
                'approved_at'     => now(),
                'status_id'       => $pendingStatusId,
                'department_id'   => $newDeptId,
            ]);

            if($user->role === 'admin'){
                $wo->update([
                    'approval_stage' => WorkOrder::STAGE_APPROVED,
                ]);
            }
        });

        $this->closeApproveModal();
        unset($this->pendingOrders);

        LivewireAlert::title('تایید شد')
            ->text('سفارش کار تایید و برای واحد انتخاب‌شده ارسال شد.')
            ->success()->timer(5000)->show();
    }

    public function openRejectModal(int $id): void
    {
        $wo = WorkOrder::query()->findOrFail($id);
        Gate::authorize('approve', $wo);

        $this->rejectingId = $id;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectingId = null;
        $this->resetValidation();
    }

    public function approveAsAdmin(int $id): void
    {
        $wo = WorkOrder::query()->findOrFail($id);
        Gate::authorize('approveAsAdmin', $wo);

        DB::transaction(function () use ($wo) {
            $user = auth()->user();

            $readyStatusId = WorkOrderStatus::query()
                ->where('key', 'in_progress')->value('id')
                ?? WorkOrderStatus::query()->where('key', 'pending')->value('id')
                ?? $wo->status_id;

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $readyStatusId,
                'changed_by'     => $user->id,
                'note'           => 'تایید نهایی توسط مدیرعامل',
                'created_at'     => now(),
            ]);

            $wo->update([
                'approval_stage'    => WorkOrder::STAGE_APPROVED,
                'approval_status'   => 1,
                'approved_by'       => $user->id,
                'approved_at'       => now(),
                'admin_approved_by' => $user->id,
                'admin_approved_at' => now(),
                'status_id'         => $readyStatusId,
            ]);
        });

        unset($this->pendingOrders);

        LivewireAlert::title('تایید شد')
            ->text('سفارش نهایی شد و آماده تخصیص توسط مدیر واحد است.')
            ->success()->timer(5000)->show();
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:1000',
        ], [
            'rejectionReason.required' => 'دلیل رد را وارد کنید.',
        ]);

        $wo = WorkOrder::query()->findOrFail($this->rejectingId);

        abort_unless(
            auth()->user()->can('approveAsManager', $wo)
            || auth()->user()->can('approveAsAdmin', $wo),
            403
        );

        DB::transaction(function () use ($wo) {
            $user = auth()->user();

            $rejectedStatusId = WorkOrderStatus::query()
                ->where('key', 'rejected')->value('id');

            $stageLabel = $wo->is_pending_manager
                ? 'رد توسط مدیر واحد'
                : 'رد توسط مدیرعامل';

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $rejectedStatusId,
                'changed_by'     => $user->id,
                'note'           => "{$stageLabel}: {$this->rejectionReason}",
                'created_at'     => now(),
            ]);

            $wo->update([
                'approval_stage'   => WorkOrder::STAGE_REJECTED,
                'approval_status'  => 2,
                'approved_by'      => $user->id,
                'rejected_at'      => now(),
                'rejection_reason' => $this->rejectionReason,
                'status_id'        => $rejectedStatusId,
            ]);
        });

        $this->closeRejectModal();
        unset($this->pendingOrders);

        LivewireAlert::title('رد شد')
            ->text('سفارش کار رد شد.')
            ->success()->timer(5000)->show();
    }

    public function render()
    {
        return view('livewire.dashboard.work-order.work-order-approval');
    }
}
