<?php

namespace App\Livewire\Dashboard\Basic;

use App\Models\WorkOrderStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts._dashboard')]
#[Title('وضعیت‌های سفارش کار')]
class WorkOrderStatusController extends Component
{

    use WithPagination;

    /* ---------- فیلترها ---------- */
    public string $search = '';
    public string $status = '';     // '' | '1' | '0'
    public string $finalFilter = ''; // '' | '1' | '0'

    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public bool $showModal = false;
    public ?int $statusId = null;

    public string $key = '';
    public string $label = '';
    public string $color = 'gray';
    public bool $is_final = false;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'key' => [
                'required', 'string', 'max:40', 'alpha_dash',
                Rule::unique('work_order_statuses', 'key')->ignore($this->statusId),
            ],
            'label'     => 'required|string|max:255',
            'color'     => 'required|in:gray,red,green,yellow,blue,purple',
            'is_final'  => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'key.required'      => 'کلید وضعیت الزامی است.',
            'key.unique'        => 'این کلید قبلاً استفاده شده است.',
            'key.alpha_dash'    => 'کلید فقط می‌تواند شامل حروف انگلیسی، اعداد، خط تیره و آندرلاین باشد.',
            'key.max'           => 'کلید نباید بیشتر از ۴۰ کاراکتر باشد.',
            'label.required'    => 'برچسب وضعیت الزامی است.',
            'color.required'    => 'رنگ را انتخاب کنید.',
            'color.in'          => 'رنگ انتخابی معتبر نیست.',
        ];
    }

    public function updatedSearch(): void      {
        $this->resetPage();
    }
    public function updatedStatus(): void      {
        $this->resetPage();
    }
    public function updatedFinalFilter(): void {
        $this->resetPage();
    }

    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset(['key', 'label', 'color', 'is_final', 'is_active']);

        if ($id) {
            $status = WorkOrderStatus::query()->findOrFail($id);

            $this->statusId  = $status->id;
            $this->key       = $status->key;
            $this->label     = $status->label;
            $this->color     = $status->color;
            $this->is_final  = (bool) $status->is_final;
            $this->is_active = (bool) $status->is_active;
        } else {
            $this->statusId  = null;
            $this->color     = 'gray';
            $this->is_final  = false;
            $this->is_active = true;
        }

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['key'] = strtolower(trim($data['key']));

        if ($this->statusId) {
            $status = WorkOrderStatus::query()->findOrFail($this->statusId);
            $status->update($data);
        } else {
            WorkOrderStatus::query()->create($data);
        }

        $this->close();

        LivewireAlert::title('موفق')
            ->text('وضعیت با موفقیت ذخیره شد.')
            ->success()
            ->timer(3000)
            ->show();
    }

    public function changeStatus(int $id): void
    {
        $status = WorkOrderStatus::query()->findOrFail($id);
        $status->update(['is_active' => ! $status->is_active]);
    }

    public function toggleFinal(int $id): void
    {
        $status = WorkOrderStatus::query()->findOrFail($id);
        $status->update(['is_final' => ! $status->is_final]);
    }

    public function delete(int $id): void
    {
        $protected = ['draft', 'pending', 'assigned', 'in_progress', 'completed', 'cancelled'];

        $status = WorkOrderStatus::query()->findOrFail($id);

        if (in_array($status->key, $protected, true)) {
            LivewireAlert::title('اخطار')
                ->text('این وضعیت پایه است و قابل حذف نیست. فقط می‌توانید غیرفعالش کنید.')
                ->warning()
                ->timer(5000)
                ->show();
            return;
        }

        $status->delete();

        LivewireAlert::title('حذف شد')
            ->text('وضعیت با موفقیت حذف شد.')
            ->success()
            ->timer(2500)
            ->show();
    }

    public function resetStatus(): void
    {
        $this->status      = '';
        $this->finalFilter = '';
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowed = ['id', 'key', 'label', 'color', 'is_final', 'is_active', 'created_at'];

        if (! in_array($field, $allowed, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function statuses(): LengthAwarePaginator
    {
        return WorkOrderStatus::query()
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($q) use ($s) {
                    $q->where('key', 'like', $s)
                        ->orWhere('label', 'like', $s);
                });
            })
            ->when($this->status !== '', fn($q) => $q->where('is_active', (bool) $this->status))
            ->when($this->finalFilter !== '', fn($q) => $q->where('is_final', (bool) $this->finalFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }

    #[Computed]
    public function colorOptions(): array
    {
        return [
            'gray'   => ['label' => 'خاکستری', 'hex' => '#6b7280'],
            'red'    => ['label' => 'قرمز',     'hex' => '#dc2626'],
            'green'  => ['label' => 'سبز',      'hex' => '#16a34a'],
            'yellow' => ['label' => 'زرد',      'hex' => '#eab308'],
            'blue'   => ['label' => 'آبی',      'hex' => '#2563eb'],
            'purple' => ['label' => 'بنفش',     'hex' => '#9333ea'],
        ];
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.basic.work-order-status');
    }
}
