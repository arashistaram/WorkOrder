<?php

namespace App\Livewire\Dashboard\Basic;

use App\Models\WorkOrderPriority;
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
#[Title('اولویت‌های سفارش کار')]
class WorkOrderPriorityManageController extends Component
{

    use WithPagination;

    public string $search = '';
    public string $status = '';       // '' | '1' | '0'
    public string $sortField = 'level';
    public string $sortDirection = 'asc';

    public bool $showModal = false;
    public ?int $priorityId = null;

    public string $key = '';
    public string $label = '';
    public string $color = 'gray';
    public int $level = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'key' => [
                'required', 'string', 'max:20', 'alpha_dash',
                Rule::unique('work_order_priorities', 'key')->ignore($this->priorityId),
            ],
            'label'     => 'required|string|max:255',
            'color'     => 'required|in:gray,red,green,yellow,blue,purple',
            'level'     => 'required|integer|min:0|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'key.required'   => 'کلید اولویت الزامی است.',
            'key.unique'     => 'این کلید قبلاً استفاده شده است.',
            'key.alpha_dash' => 'کلید فقط می‌تواند شامل حروف انگلیسی، اعداد، خط تیره و آندرلاین باشد.',
            'key.max'        => 'کلید نباید بیشتر از ۲۰ کاراکتر باشد.',
            'label.required' => 'برچسب اولویت الزامی است.',
            'color.required' => 'رنگ را انتخاب کنید.',
            'color.in'       => 'رنگ انتخابی معتبر نیست.',
            'level.required' => 'سطح اولویت الزامی است.',
            'level.integer'  => 'سطح اولویت باید عدد باشد.',
            'level.min'      => 'سطح اولویت نمی‌تواند منفی باشد.',
            'level.max'      => 'سطح اولویت حداکثر ۲۵۵ است.',
        ];
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    /* ==================== Open / Close ==================== */
    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset(['key', 'label', 'color', 'level', 'is_active']);

        if ($id) {
            $p = WorkOrderPriority::query()->findOrFail($id);

            $this->priorityId = $p->id;
            $this->key        = $p->key;
            $this->label      = $p->label;
            $this->color      = $p->color;
            $this->level      = (int) $p->level;
            $this->is_active  = (bool) $p->is_active;
        } else {
            $this->priorityId = null;
            $this->color      = 'gray';
            $this->level      = (int) (WorkOrderPriority::query()->max('level') + 1);
            $this->is_active  = true;
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

        if ($this->priorityId) {
            $p = WorkOrderPriority::query()->findOrFail($this->priorityId);
            $p->update($data);
        } else {
            WorkOrderPriority::query()->create($data);
        }

        $this->close();

        LivewireAlert::title('موفق')
            ->text('اولویت با موفقیت ذخیره شد.')
            ->success()
            ->timer(3000)
            ->show();
    }

    public function changeStatus(int $id): void
    {
        $p = WorkOrderPriority::query()->findOrFail($id);
        $p->update(['is_active' => ! $p->is_active]);
    }

    public function delete(int $id): void
    {
        $protected = ['low', 'medium', 'high', 'critical'];

        $p = WorkOrderPriority::query()->findOrFail($id);

        if (in_array($p->key, $protected, true)) {
            LivewireAlert::title('اخطار')
                ->text('این اولویت پایه است و قابل حذف نیست. فقط می‌توانید غیرفعالش کنید.')
                ->warning()
                ->timer(5000)
                ->show();
            return;
        }

        $p->delete();

        LivewireAlert::title('حذف شد')
            ->text('اولویت با موفقیت حذف شد.')
            ->success()
            ->timer(2500)
            ->show();
    }

    public function resetStatus(): void
    {
        $this->status = '';
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowed = ['id', 'key', 'label', 'color', 'level', 'is_active', 'created_at'];

        if (! in_array($field, $allowed, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }
    #[Computed]
    public function priorities(): LengthAwarePaginator
    {
        return WorkOrderPriority::query()
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($q) use ($s) {
                    $q->where('key', 'like', $s)
                        ->orWhere('label', 'like', $s);
                });
            })
            ->when($this->status !== '', fn($q) => $q->where('is_active', (bool) $this->status))
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
        return view('livewire.dashboard.basic.work-order-priority-manage');
    }
}
