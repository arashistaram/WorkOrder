<?php

namespace App\Livewire\Dashboard\WorkOrder;

use App\Models\Department;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderChecklistItem;
use App\Models\WorkOrderStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('جزئیات سفارش کار')]
class WorkOrderDetailController extends Component
{
    public int $workOrderId;

    public bool $showAssignModal = false;
    public ?int $assignToUserId   = null;
    public ?int $assignToDeptId   = null;
    public string $assignNote     = '';

    public bool $showStatusModal = false;
    public ?int $newStatusId     = null;
    public string $statusNote    = '';

    public string $newChecklistTitle = '';

    public ?float $actualHours = null;

    public function mount(int $id): void
    {
        $this->workOrderId = $id;

        $wo = $this->workOrder;

        Gate::authorize('view', $wo);

        $this->actualHours = $wo->actual_hours;
    }

    #[Computed]
    public function workOrder(): WorkOrder
    {
        return WorkOrder::with([
            'department', 'assignee', 'assignedBy', 'creator',
            'status', 'priority',
            'assignments.assignedTo', 'assignments.assignedBy',
            'assignments.fromDepartment', 'assignments.toDepartment',
            'statusHistories.fromStatus', 'statusHistories.toStatus', 'statusHistories.changedBy',
            'checklistItems.doneBy',
        ])->findOrFail($this->workOrderId);
    }


    public function statusesList()
    {
        return WorkOrderStatus::query()->active()->orderBy('id')->get(['id', 'label', 'color']);
    }

    #[Computed]
    public function usersList(): Collection
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function departmentsList(): Collection
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }

    public function openAssignModal(): void
    {
        $this->reset(['assignToUserId', 'assignToDeptId', 'assignNote']);
        $this->assignToUserId = $this->workOrder->assignee_id;
        $this->assignToDeptId = $this->workOrder->department_id;
        $this->showAssignModal = true;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->resetValidation();
    }

    public function assign(): void
    {
        Gate::authorize('assign', $this->workOrder);

        $this->validate([
            'assignToUserId' => 'required|exists:users,id',
            'assignToDeptId' => 'required|exists:departments,id',
            'assignNote'     => 'nullable|string|max:1000',
        ], [
            'assignToUserId.required' => 'کاربر مسئول را انتخاب کنید.',
            'assignToDeptId.required' => 'واحد مقصد را انتخاب کنید.',
        ]);

        DB::transaction(function () {
            $wo = $this->workOrder;

            $wo->assignments()->whereNull('unassigned_at')
                ->update(['unassigned_at' => now()]);

            $wo->assignments()->create([
                'assigned_to'        => $this->assignToUserId,
                'assigned_by'        => auth()->id(),
                'from_department_id' => $wo->department_id,
                'to_department_id'   => $this->assignToDeptId,
                'note'               => $this->assignNote,
                'assigned_at'        => now(),
            ]);

            $wo->update([
                'assignee_id'    => $this->assignToUserId,
                'assigned_by'    => auth()->id(),
                'assigned_at'    => now(),
                'department_id'  => $this->assignToDeptId,
            ]);
        });

        unset($this->workOrder); // refresh
        $this->closeAssignModal();

        LivewireAlert::title('موفق')->text('کاربر تخصیص داده شد.')
            ->success()->timer(3000)->show();
    }

    public function openStatusModal(): void
    {
        $this->newStatusId = $this->workOrder->status_id;
        $this->statusNote  = '';
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->resetValidation();
    }

    public function changeStatus(): void
    {
        Gate::authorize('changeStatus', $this->workOrder);

        $this->validate([
            'newStatusId' => 'required|exists:work_order_statuses,id',
            'statusNote'  => 'nullable|string|max:1000',
        ], [
            'newStatusId.required' => 'وضعیت جدید را انتخاب کنید.',
        ]);

        $wo = $this->workOrder;

        if ((int) $wo->status_id === (int) $this->newStatusId) {
            LivewireAlert::title('توجه')->text('وضعیت فعلی همان وضعیت انتخابی است.')
                ->info()->timer(2500)->show();
            return;
        }

        DB::transaction(function () use ($wo) {
            $newStatus = WorkOrderStatus::query()->findOrFail($this->newStatusId);

            $wo->statusHistories()->create([
                'from_status_id' => $wo->status_id,
                'to_status_id'   => $this->newStatusId,
                'changed_by'     => auth()->id(),
                'note'           => $this->statusNote,
                'created_at'     => now(),
            ]);

            $updates = ['status_id' => $this->newStatusId];

            // آپدیت timestamp های مربوطه
            if ($newStatus->key === 'in_progress' && ! $wo->started_at) {
                $updates['started_at'] = now();
            }
            if ($newStatus->key === 'completed') {
                $updates['completed_at'] = now();
            }
            if ($newStatus->key === 'cancelled') {
                $updates['cancelled_at'] = now();
            }

            $wo->update($updates);
        });

        unset($this->workOrder);
        $this->closeStatusModal();

        LivewireAlert::title('موفق')->text('وضعیت تغییر کرد.')
            ->success()->timer(3000)->show();
    }

    public function addChecklistItem(): void
    {
        Gate::authorize('manageChecklist', $this->workOrder);

        $this->validate([
            'newChecklistTitle' => 'required|string|max:255',
        ], [
            'newChecklistTitle.required' => 'عنوان آیتم را وارد کنید.',
        ]);

        $maxSort = $this->workOrder->checklistItems()->max('sort_order') ?? 0;

        $this->workOrder->checklistItems()->create([
            'title'      => $this->newChecklistTitle,
            'sort_order' => $maxSort + 1,
        ]);

        $this->newChecklistTitle = '';
        unset($this->workOrder);
    }

    public function toggleChecklistItem(int $itemId): void
    {
        Gate::authorize('manageChecklist', $this->workOrder);

        $item = WorkOrderChecklistItem::query()
            ->where('work_order_id', $this->workOrderId)
            ->findOrFail($itemId);

        $item->update([
            'is_done' => ! $item->is_done,
            'done_by' => $item->is_done ? null : auth()->id(),
            'done_at' => $item->is_done ? null : now(),
        ]);

        unset($this->workOrder);
    }

    public function removeChecklistItem(int $itemId): void
    {
        Gate::authorize('manageChecklist', $this->workOrder);

        WorkOrderChecklistItem::query()
            ->where('work_order_id', $this->workOrderId)
            ->findOrFail($itemId)
            ->delete();

        unset($this->workOrder);
    }

    public function saveActualHours(): void
    {
        Gate::authorize('changeStatus', $this->workOrder);

        $this->validate([
            'actualHours' => 'nullable|numeric|min:0|max:9999',
        ]);

        $this->workOrder->update(['actual_hours' => $this->actualHours]);
        unset($this->workOrder);

        LivewireAlert::title('موفق')->text('ساعات واقعی ذخیره شد.')
            ->success()->timer(2000)->show();
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.work-order.work-order-detail');
    }
}
