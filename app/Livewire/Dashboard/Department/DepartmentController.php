<?php

namespace App\Livewire\Dashboard\Department;

use App\Models\Department;
use App\Models\DepartmentUser;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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

    #[Url(history: true)]
    public ?string $status = '';

    #[Url(history: true)]
    public string $sortField = 'id';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

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


    public bool $showAssignModal = false;
    public ?int $assignDepartmentId = null;
    public string $assignSearch = '';

    public array $assignRows = [];
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

    protected array $sortable = [
        'id',
        'name',
        'code',
        'phone',
        'is_active',
        'location',
        'created_at',
    ];

    public function openAssign(): void
    {
        $this->resetValidation();
        $this->assignDepartmentId = null;
        $this->assignSearch       = '';
        $this->assignRows         = [];
        $this->showAssignModal    = true;
    }

    public function closeAssign(): void
    {
        $this->showAssignModal = false;
        $this->resetValidation();
    }

    public function updatedAssignDepartmentId(): void
    {
        $this->assignRows = $this->buildAssignRows();
    }

    public function updatedAssignSearch(): void
    {
        $this->assignRows = $this->buildAssignRows();
    }

    protected function buildAssignRows(): array
    {
        if (! $this->assignDepartmentId) return [];

        $dept = Department::with('users')->find($this->assignDepartmentId);
        if (! $dept) return [];

        $existing = $dept->users->keyBy('id');

        $users = User::query()
            ->where('is_active', true)
            ->when($this->assignSearch !== '', function ($q) {
                $s = '%' . $this->assignSearch . '%';
                $q->where(fn($qq) =>
                $qq->where('name', 'like', $s)
                    ->orWhere('username', 'like', $s)
                );
            })
            ->orderBy('name')
            ->get(['id', 'name', 'username']);


        $rows = [];
        foreach ($users as $u) {
            $isMember = $existing->has($u->id);
            $rows[$u->id] = [
                'name'     => $u->name ?? $u->username,
                'username' => $u->username,
                'selected' => $isMember,
                'role'     => $isMember ? ($existing[$u->id]->pivot->role ?? 'user') : 'user',
            ];
        }
        return $rows;
    }

    public function saveAssign(): void
    {
        $this->validate([
            'assignDepartmentId'    => 'required|exists:departments,id',
            'assignRows'            => 'array',
            'assignRows.*.selected' => 'boolean',
            'assignRows.*.role'     => 'in:manager,user',
        ], [
            'assignDepartmentId.required' => 'یک واحد را انتخاب کنید.',
            'assignDepartmentId.exists'   => 'واحد انتخابی معتبر نیست.',
        ]);

        $dept = Department::query()->findOrFail($this->assignDepartmentId);

        $sync = [];
        foreach ($this->assignRows as $userId => $row) {
            if (!empty($row['selected'])) {
                $sync[$userId] = [
                    'role'       => $row['role'] ?? 'user',
                    'is_active'  => true,
                    'joined_at'  => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $dept->users()->sync($sync);

        $this->closeAssign();
        $this->dispatch('notify', type: 'success', message: 'تخصیص کاربران ذخیره شد.');
    }

    /* ==================== Computed ==================== */
    #[Computed]
    public function departmentsList(): Collection
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if (! in_array($field, $this->sortable, true)) {
            return;
        }
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[Computed]
    public function departments(): LengthAwarePaginator
    {
        return Department::query()
            ->with(['users:id,name'])
            ->when($this->status !== '' && $this->status !== null, function ($query) {
                $query->where('is_active', (bool) $this->status);
            })
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
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function resetStatus(): void
    {
        $this->status = null;
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

    public function changeStatus(int $id): void
    {
        $department = Department::query()->findOrFail($id);
        $department->update(['is_active' => ! $department->is_active]);
    }

    public function delete(int $id): void
    {
        Department::query()->findOrFail($id)->delete();
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
            $dep = Department::query()->with('users')->findOrFail($id);
            $this->name        = $dep->name ?? '';
            $this->code        = $dep->code ?? '';
            $this->description = $dep->description ?? '';
            $this->location    = $dep->location ?? '';
            $this->phone       = $dep->phone ?? '';
            $this->color       = $dep->color ?? '';
            $this->managerId   = $dep->users[0]->id;
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

        if (! $this->departmentId && empty($this->managerId)) {
            $this->addError('managerId', 'انتخاب سرپرست واحد الزامی است.');
            return;
        }

        try {
            DB::transaction(function () use ($data) {
                if ($this->departmentId) {
                    Department::query()
                        ->findOrFail($this->departmentId)
                        ->update($data);

                    DepartmentUser::query()
                        ->where('department_id', $this->departmentId)
                        ->update(['user_id' => $this->managerId,]);

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
