<?php

namespace App\Livewire\Dashboard\Department;

use App\Models\Department;
use App\Models\DepartmentUser;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts._dashboard')]
#[Title('واحد ها')]
final class DepartmentController extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public int $perPage = 10;

    public bool $showModal = false;
    public ?int $departmentId = null;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $color     = '';
    public string $phone       = '';
    public string $location    = '';

    public ?int $managerId   = null;

    public bool $is_active   = true;

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


    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function departments(): LengthAwarePaginator
    {
        return Department::query()
            ->with(['users:id,name'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhereHas('users', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->latest('id')
            ->paginate($this->perPage);
    }
    protected function messages(): array
    {
        return [
            'name.required' => 'نام واحد الزامی است.',
            'name.min'      => 'نام واحد باید حداقل ۲ حرف باشد.',
            'code.unique'   => 'این کد قبلاً استفاده شده است.',
        ];
    }

    public function getUsers(): array
    {
        return User::query()->select('id', 'name')
            ->where('is_active', 1)
            ->pluck('id', 'name')
            ->toArray();
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

        try {
            DB::transaction(function () use ($data) {
                if ($this->departmentId) {
                    Department::query()
                        ->findOrFail($this->departmentId)
                        ->update($data);
                } else {
                    $department = Department::query()->create($data);

                    DepartmentUser::query()->create([
                        'department_id' => $department['id'],
                        'user_id'       => $this->managerId,
                        'role'          => DepartmentUser::ROLE_MANAGER,
                        'is_active'     => $this->is_active,
                        'joined_at'     => now(),
                    ]);
                }
            });

            $this->close();

        } catch (\Throwable $e) {
            logger()->error('خطا در ذخیره دپارتمان', [
                'message' => $e->getMessage(),
                'data'    => $data,
            ]);

            LivewireAlert::title('Error')
                ->text('خطا در ذخیره اطلاعات. لطفاً مجدداً تلاش کنید.')
                ->error()
                ->timer(5000)
                ->show();

        }
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.department.department');
    }
}
