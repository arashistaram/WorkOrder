<?php

namespace App\Livewire\Dashboard\Department;

use App\Models\Department;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Layout('livewire.layouts._dashboard')]
#[Title('واحد ها')]
final class DepartmentController extends Component
{
    public bool $showModal = false;
    public ?int $departmentId = null;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $color     = '';
    public string $phone       = '';
    public string $location    = '';

    public bool   $is_active   = true;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|min:2|max:255',
            'code'        => 'nullable|string|max:50|unique:departments,code,' . $this->departmentId,
            'description' => 'nullable|string|max:2000',
            'color'       => 'nullable|string|max:50',
            'location'    => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'نام واحد الزامی است.',
            'name.min'      => 'نام واحد باید حداقل ۲ حرف باشد.',
            'code.unique'   => 'این کد قبلاً استفاده شده است.',
        ];
    }

    #[On('open-department-form')]
    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset([
            'name', 'code', 'description',
            'location', 'phone', 'color', 'is_active'
        ]);
        $this->is_active    = true;
        $this->departmentId = $id;

        if ($id) {
            $dep = Department::query()->findOrFail($id);
            $this->name        = $dep->name ?? '';
            $this->code        = $dep->code ?? '';
            $this->description = $dep->description ?? '';
            $this->location    = $dep->location ?? '';
            $this->phone       = $dep->phone ?? '';
            $this->color       = $dep->color ?? '';
            $this->is_active   = (bool) $dep->is_active;
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

        if ($this->departmentId) {
            Department::query()->findOrFail($this->departmentId)->update($data);
            $this->dispatch('department-updated');
            $this->dispatch('toast', message: 'واحد به‌روزرسانی شد', type: 'success');
        } else {
            Department::query()->create($data);
            $this->dispatch('department-created');
            $this->dispatch('toast', message: 'واحد جدید ایجاد شد', type: 'success');
        }

        $this->close();
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.department.department');
    }
}
