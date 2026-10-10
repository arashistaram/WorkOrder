<?php

namespace App\Livewire\Dashboard\WorkOrder;

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
    public bool $showRejectModal = false;
    public ?int $rejectingId = null;
    public string $rejectionReason = '';

    public bool $showApproveModal = false;
    public ?int $approvingId      = null;
    public ?int $approveToDepartmentId = null;

    #[Computed]
    public function pendingOrders(): LengthAwarePaginator
    {
        $user = auth()->user();

        return WorkOrder::query()
            ->with(['department', 'creator', 'priority'])
            ->where('approval_status', 0)
            ->when(true, function ($q) use ($user) {

                $supervisedIds = $user->effectiveSupervisedDepartmentIds();

                $managedIds    = $user->effectiveManagedDepartmentIds();

                $deptIds = $supervisedIds->merge($managedIds)->unique();

                if ($deptIds->isEmpty()) {
                    $q->whereRaw('1=0');
                } else {
                    $q->whereIn('department_id', $deptIds);
                }
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

        if (in_array($user->role, ['manager', 'admin'])) {
            return \App\Models\Department::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

//        return $user->managedDepartments()
//            ->orderBy('departments.name')
//            ->get(['departments.id', 'departments.name']);
    }

    public function openApproveModal(int $id): void
    {
        $wo = WorkOrder::query()->findOrFail($id);
        Gate::authorize('approve', $wo);

        $this->approvingId            = $id;
        $this->approveToDepartmentId  = $wo->department_id; // پیش‌فرض واحد فعلی
        $this->showApproveModal       = true;
    }

    public function closeApproveModal(): void
    {
        $this->showApproveModal = false;
        $this->approvingId = null;
        $this->approveToDepartmentId = null;
        $this->resetValidation();
    }

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

            $note = 'تایید توسط مدیر';

            // اگر واحد عوض شد، در تاریخچه توضیح بده
            if ($oldDeptId !== $newDeptId) {
                $oldName = \App\Models\Department::find($oldDeptId)?->name;
                $newName = \App\Models\Department::find($newDeptId)?->name;
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
        });

        $this->closeApproveModal();
        unset($this->pendingOrders);

        LivewireAlert::title('تایید شد')
            ->text('سفارش کار تایید و برای واحد انتخاب‌شده ارسال شد.')
            ->success()->timer(2500)->show();
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

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:1000',
        ], [
            'rejectionReason.required' => 'دلیل رد را وارد کنید.',
        ]);

        $wo = WorkOrder::query()->findOrFail($this->rejectingId);
        Gate::authorize('approve', $wo);

        DB::transaction(function () use ($wo) {
            $user = auth()->user();

            $rejectedStatusId = WorkOrderStatus::query()
                ->where('key', 'rejected')->value('id');

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $rejectedStatusId,
                'changed_by'     => $user->id,
                'note'           => 'رد شده: ' . $this->rejectionReason,
                'created_at'     => now(),
            ]);

            $wo->update([
                'approval_status'  => 2,
                'approved_by'      => $user->id,
                'rejected_at'      => now(),
                'rejection_reason' => $this->rejectionReason,
                'status_id'        => $rejectedStatusId,
            ]);
        });

        $this->closeRejectModal();

        LivewireAlert::title('رد شد')
            ->text('سفارش کار رد شد.')
            ->success()->timer(2500)->show();
    }

    public function render()
    {
        return view('livewire.dashboard.work-order.work-order-approval');
    }
}
