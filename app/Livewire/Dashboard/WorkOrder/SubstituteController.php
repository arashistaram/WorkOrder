<?php

namespace App\Livewire\Dashboard\WorkOrder;

use App\Models\Department;
use App\Models\DepartmentSubstitute;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('جانشین‌های من')]
class SubstituteController extends Component
{
    public bool $showModal = false;

    public ?int $department_id      = null;
    public ?int $user_id            = null;
    public ?string $starts_at       = null;
    public ?string $ends_at         = null;
    public ?string $reason          = null;
    public string $role             = 'supervisor';

    protected function rules(): array
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'user_id'       => 'required|exists:users,id',
            'role'          => 'required|in:supervisor,manager',
            'starts_at'     => 'nullable|string|max:20',
            'ends_at'       => 'nullable|string|max:20',
            'reason'        => 'nullable|string|max:500',
        ];
    }

    protected function messages(): array
    {
        return [
            'department_id.required' => 'واحد را انتخاب کنید.',
            'user_id.required'       => 'جانشین را انتخاب کنید.',
        ];
    }

    #[Computed]
    public function myDepartments()
    {
        $ids = auth()->user()->supervisedDepartments()->pluck('departments.id')
            ->merge(auth()->user()->managedDepartments()->pluck('departments.id'))
            ->unique();

        return Department::query()->whereIn('id', $ids)->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function departmentMembers(): Collection|\Illuminate\Support\Collection
    {
        if (! $this->department_id) return collect();

        return User::query()
            ->where('is_active', true)
            ->where('id', '!=', auth()->id())
            ->whereHas('departments', function ($q) {
                $q->where('departments.id', $this->department_id)
                    ->where('department_users.is_active', true);
            })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function mySubstitutes(): Collection
    {
        return DepartmentSubstitute::query()
            ->with(['user', 'department'])
            ->where('substitute_for_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function openModal(): void
    {
        $this->reset([
            'department_id', 'user_id', 'starts_at', 'ends_at',
            'reason', 'role',
        ]);
        $this->role = 'supervisor';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function updatedDepartmentId(): void
    {
        $this->user_id = null;
        unset($this->departmentMembers);
    }

    public function save(): void
    {
        $data = $this->validate();

        $user = auth()->user();
        $isSupervisor = $user->supervisedDepartments()->whereKey($data['department_id'])->exists();
        $isManager    = $user->managedDepartments()->whereKey($data['department_id'])->exists();

        if (! $isSupervisor && ! $isManager) {
            $this->addError('department_id', 'شما در این واحد سرپرست یا مدیر نیستید.');
            return;
        }

        $belongs = DB::table('department_users')
            ->where('department_id', $data['department_id'])
            ->where('user_id', $data['user_id'])
            ->where('is_active', true)
            ->exists();

        if (! $belongs) {
            $this->addError('user_id', 'جانشین باید عضو فعال همان واحد باشد.');
            return;
        }

        $startsAt = $this->toGregorian($data['starts_at']);
        $endsAt   = $this->toGregorian($data['ends_at'], endOfDay: true);

        DepartmentSubstitute::query()->create([
            'user_id'           => $data['user_id'],
            'substitute_for_id' => auth()->id(),
            'department_id'     => $data['department_id'],
            'role'              => $data['role'],
            'starts_at'         => $startsAt,
            'ends_at'           => $endsAt,
            'reason'            => $data['reason'],
            'is_active'         => true,
            'created_by'        => auth()->id(),
        ]);

        $this->closeModal();
        unset($this->mySubstitutes);

        LivewireAlert::title('ثبت شد')
            ->text('جانشین با موفقیت تعیین شد.')
            ->success()->timer(2500)->show();
    }

    public function toggle(int $id): void
    {
        $sub = DepartmentSubstitute::query()
            ->where('substitute_for_id', auth()->id())
            ->findOrFail($id);

        $sub->update(['is_active' => ! $sub->is_active]);

        unset($this->mySubstitutes);

        LivewireAlert::title('انجام شد')
            ->text($sub->is_active ? 'جانشین فعال شد.' : 'جانشین غیرفعال شد.')
            ->success()->timer(2000)->show();
    }

    public function delete(int $id): void
    {
        DepartmentSubstitute::query()
            ->where('substitute_for_id', auth()->id())
            ->findOrFail($id)
            ->delete();

        unset($this->mySubstitutes);

        LivewireAlert::title('حذف شد')->success()->timer(1800)->show();
    }

    protected function toGregorian(?string $jalali, bool $endOfDay = false): ?string
    {
        if (! $jalali) return null;

        // نرمال‌سازی جداکننده‌ها: 1405-07-18 → 1405/07/18
        $jalali = str_replace(['-', '.'], '/', trim($jalali));

        try {
            $carbon = \Hekmatinasser\Verta\Verta::parse($jalali)->toCarbon();

            return $endOfDay
                ? $carbon->endOfDay()->toDateTimeString()
                : $carbon->startOfDay()->toDateTimeString();

        } catch (\Throwable $e) {
            \Log::error('toGregorian failed', [
                'input' => $jalali,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.work-order.substitute');
    }
}
