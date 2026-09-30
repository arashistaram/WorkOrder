<?php

namespace App\Livewire\Dashboard\WorkOrder;

use App\Models\Department;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderPriority;
use App\Models\WorkOrderStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts._dashboard')]
#[Title('سفارش کار ها')]
final class WorkOrderManageController extends Component
{
    use WithPagination;

    public string $search         = '';
    public string $statusFilter   = '';
    public string $priorityFilter = '';
    public string $deptFilter     = '';
    public string $assigneeFilter = '';
    public string $overdueFilter  = '';  // '' | '1'

    public string $sortField     = 'id';
    public string $sortDirection = 'desc';

    public bool $showModal = false;
    public ?int $workOrderId = null;

    public string $title         = '';
    public string $description   = '';
    public ?int $department_id   = null;
    public ?int $status_id       = null;
    public ?int $priority_id     = null;
//    public ?int $assignee_id     = null;
//    public ?string $due_date     = null;
    public ?float $estimated_hours = null;

    public ?int $due_year  = null;   // 1403
    public ?int $due_month = null;   // 1..12
    public ?int $due_day   = null;   // 1..31

    protected function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'department_id'   => 'required|exists:departments,id',
            'status_id'       => 'required|exists:work_order_statuses,id',
            'priority_id'     => 'required|exists:work_order_priorities,id',
            'due_year'  => 'nullable|integer|min:1300|max:1500',
            'due_month' => 'nullable|integer|min:1|max:12',
            'due_day'   => 'nullable|integer|min:1|max:31',
//            'assignee_id'     => 'nullable|exists:users,id',
//            'due_date'        => 'nullable|date',
//            'estimated_hours' => 'nullable|numeric|min:0|max:9999',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'         => 'عنوان سفارش کار الزامی است.',
            'department_id.required' => 'واحد را انتخاب کنید.',
            'department_id.exists'   => 'واحد انتخابی معتبر نیست.',
            'status_id.required'     => 'وضعیت را انتخاب کنید.',
            'priority_id.required'   => 'اولویت را انتخاب کنید.',
            'due_year.integer'       => 'سال معتبر نیست.',
            'due_year.min'           => 'سال باید حداقل ۱۳۰۰ باشد.',
            'due_year.max'           => 'سال باید حداکثر ۱۵۰۰ باشد.',
            'due_month.min'          => 'ماه باید بین ۱ تا ۱۲ باشد.',
            'due_month.max'          => 'ماه باید بین ۱ تا ۱۲ باشد.',
            'due_day.min'            => 'روز باید بین ۱ تا ۳۱ باشد.',
            'due_day.max'            => 'روز باید بین ۱ تا ۳۱ باشد.',
        ];
    }

    #[Computed]
    public function persianMonths(): array
    {
        return [
            1  => 'فروردین',
            2  => 'اردیبهشت',
            3  => 'خرداد',
            4  => 'تیر',
            5  => 'مرداد',
            6  => 'شهریور',
            7  => 'مهر',
            8  => 'آبان',
            9  => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
        ];
    }

    #[Computed]
    public function persianYears(): array
    {
        $current = (int) verta()->format('Y');
        $years = [];
        for ($y = $current - 1; $y <= $current + 5; $y++) {
            $years[$y] = $y;
        }
        return $years;
    }

    public function updatedSearch(): void         {
        $this->resetPage();
    }
    public function updatedStatusFilter(): void   {
        $this->resetPage();
    }
    public function updatedPriorityFilter(): void {
        $this->resetPage();
    }
    public function updatedDeptFilter(): void     {
        $this->resetPage();
    }
    public function updatedAssigneeFilter(): void {
        $this->resetPage();
    }
    public function updatedOverdueFilter(): void  {
        $this->resetPage();
    }

    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset([
            'title', 'description', 'department_id',
            'status_id', 'priority_id',
            'due_year', 'due_month', 'due_day',
            'estimated_hours',
        ]);

        if ($id) {
            $wo = WorkOrder::query()->findOrFail($id);

            $this->workOrderId     = $wo->id;
            $this->title           = $wo->title;
            $this->description     = $wo->description ?? '';
            $this->department_id   = $wo->department_id;
            $this->status_id       = $wo->status_id;
            $this->priority_id     = $wo->priority_id;
            $this->estimated_hours = $wo->estimated_hours;

            if ($wo->due_date) {
                $v = verta($wo->due_date);
                $this->due_year  = (int) $v->format('Y');
                $this->due_month = (int) $v->format('n');
                $this->due_day   = (int) $v->format('j');
            }
        } else {
            $this->workOrderId = null;

            $this->status_id = WorkOrderStatus::query()
                ->where('key', 'pending')->value('id')
                ?? WorkOrderStatus::query()->active()->value('id');

            $this->priority_id = WorkOrderPriority::query()
                ->where('key', 'medium')->value('id')
                ?? WorkOrderPriority::query()->active()->value('id');

            $this->department_id = auth()->user()?->departments()->first()?->id;
        }

        unset($this->statusesList);

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    /**
     * @throws \Throwable
     */
    public function save(): void
    {
        $data = $this->validate();

        // تبدیل تاریخ شمسی به میلادی
        $data['due_date'] = $this->buildGregorianDueDate();

        // این سه فیلد نباید مستقیم ذخیره بشن
        unset($data['due_year'], $data['due_month'], $data['due_day']);

        DB::transaction(function () use ($data) {
            if ($this->workOrderId) {
                $wo = WorkOrder::query()->findOrFail($this->workOrderId);

                if ((int) $wo->status_id !== (int) $data['status_id']) {
                    $this->recordStatusChange($wo, $data['status_id'], 'تغییر از فرم ویرایش');
                }

                unset($data['assignee_id'], $data['assigned_by'], $data['assigned_at']);
                $wo->update($data);
            } else {
                $data['code']        = WorkOrder::generateCode();
                $data['created_by']  = auth()->id();
                $data['assignee_id'] = null;
                $data['assigned_by'] = null;
                $data['assigned_at'] = null;

                $wo = WorkOrder::query()->create($data);
                $this->recordStatusChange($wo, $data['status_id'], 'ایجاد سفارش کار', true);
            }
        });

        $this->close();

        LivewireAlert::title('موفق')
            ->text('سفارش کار با موفقیت ذخیره شد.')
            ->success()->timer(3000)->show();
    }

    protected function recordStatusChange(WorkOrder $wo, int $toStatusId, ?string $note = null, bool $isCreation = false): void
    {
        $wo->statusHistories()->create([
            'from_status_id' => $isCreation ? null : $wo->status_id,
            'to_status_id'   => $toStatusId,
            'changed_by'     => auth()->id(),
            'note'           => $note,
            'created_at'     => now(),
        ]);
    }

    protected function buildGregorianDueDate(): ?string
    {
        if (! $this->due_year || ! $this->due_month || ! $this->due_day) {
            return null;
        }

        try {
            $v = verta()
                ->year($this->due_year)
                ->month($this->due_month)
                ->day($this->due_day);

            return $v->toCarbon()->format('Y-m-d');

        } catch (\Throwable $e) {
            \Log::error('buildGregorianDueDate failed', [
                'y' => $this->due_year,
                'm' => $this->due_month,
                'd' => $this->due_day,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function updatedDueMonth(): void
    {
        if ($this->due_year && $this->due_month && $this->due_day) {
            $max = verta()->daysInMonth($this->due_year, $this->due_month);
            if ($this->due_day > $max) {
                $this->due_day = null;
            }
        }
    }

    public function clearDueDate(): void
    {
        $this->due_year  = null;
        $this->due_month = null;
        $this->due_day   = null;
    }

    public function updatedDueYear(): void
    {
        $this->updatedDueMonth();
    }

    /* ==================== Actions ==================== */
    public function delete(int $id): void
    {
        WorkOrder::query()->findOrFail($id)->delete();

        LivewireAlert::title('حذف شد')
            ->text('سفارش کار با موفقیت حذف شد.')
            ->success()
            ->timer(2500)
            ->show();
    }

    public function resetFilters(): void
    {
        $this->statusFilter   = '';
        $this->priorityFilter = '';
        $this->deptFilter     = '';
        $this->assigneeFilter = '';
        $this->overdueFilter  = '';
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowed = ['id', 'code', 'title', 'due_date', 'created_at', 'status_id', 'priority_id'];

        if (! in_array($field, $allowed, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function workOrders(): LengthAwarePaginator
    {
        return WorkOrder::query()
            ->with(['department', 'assignee', 'status', 'priority','checklistItems'])
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(fn($qq) => $qq
                    ->where('code', 'like', $s)
                    ->orWhere('title', 'like', $s)
                    ->orWhere('description', 'like', $s)
                );
            })
            ->when($this->statusFilter !== '', fn($q) => $q->where('status_id', $this->statusFilter))
            ->when($this->priorityFilter !== '', fn($q) => $q->where('priority_id', $this->priorityFilter))
            ->when($this->deptFilter !== '', fn($q) => $q->where('department_id', $this->deptFilter))
            ->when($this->assigneeFilter !== '', fn($q) => $q->where('assignee_id', $this->assigneeFilter))
            ->when($this->overdueFilter === '1', fn($q) => $q->overdue())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }

    #[Computed]
    public function departmentsList(): Collection
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function statusesList(): Collection
    {
        $query = WorkOrderStatus::query()
            ->where('is_active', true)
            ->orderBy('id');

        if (! $this->workOrderId) {
            $query->whereIn('key', ['draft', 'pending']);
        }

        return $query->get(['id', 'label', 'color', 'key']);
    }

    #[Computed]
    public function allStatusesList(): Collection
    {
        $query = WorkOrderStatus::query()
            ->where('is_active', true)
            ->orderBy('id');

        return $query->get(['id', 'label', 'color', 'key']);
    }

    #[Computed]
    public function prioritiesList()
    {
        return WorkOrderPriority::query()->active()->ordered()->get(['id', 'label', 'color', 'level']);
    }

    #[Computed]
    public function usersList(): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'username']);
    }
    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.work-order.work-order');
    }
}
