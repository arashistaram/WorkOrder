<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
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
#[Title('کاربران')]
class UserManageController extends Component
{

    use WithPagination;

    public string $search = '';
    public string $status = '';        // '' | '1' | '0'
    public string $roleFilter = '';    // '' | 'admin' | 'manager' | 'user'

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public bool $showModal = false;
    public ?int $userId = null;

    public string $name = '';
    public string $username = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';
    public string $phone = '';
    public string $employee_code = '';
    public string $job_title = '';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name'            => 'nullable|string|max:255',
            'username'        => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('users', 'username')->ignore($this->userId),
            ],
            'password'        => $this->userId
                ? 'nullable|string|min:6|confirmed'
                : 'required|string|min:6|confirmed',
            'role'            => 'required|in:admin,manager,user',
            'phone'           => 'nullable|string|max:20',
            'employee_code'   => [
                'nullable', 'string', 'max:40',
                Rule::unique('users', 'employee_code')->ignore($this->userId),
            ],
            'job_title'       => 'nullable|string|max:255',
            'is_active'       => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'username.required'    => 'نام کاربری الزامی است.',
            'username.unique'      => 'این نام کاربری قبلاً ثبت شده است.',
            'username.alpha_dash'  => 'نام کاربری فقط می‌تواند شامل حروف، اعداد، خط تیره و آندرلاین باشد.',
            'password.required'    => 'رمز عبور الزامی است.',
            'password.min'         => 'رمز عبور باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed'   => 'تکرار رمز عبور مطابقت ندارد.',
            'role.required'        => 'نقش کاربر را انتخاب کنید.',
            'employee_code.unique' => 'این کد پرسنلی قبلاً ثبت شده است.',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedStatus(): void
    {
        $this->resetPage();
    }
    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset([
            'name', 'username', 'password', 'password_confirmation',
            'phone', 'employee_code', 'job_title',
        ]);

        if ($id) {
            $user = User::query()->findOrFail($id);
            $this->userId        = $user->id;
            $this->name          = $user->name ?? '';
            $this->username      = $user->username;
            $this->role          = $user->role;
            $this->phone         = $user->phone ?? '';
            $this->employee_code = $user->employee_code ?? '';
            $this->job_title     = $user->job_title ?? '';
            $this->is_active     = (bool) $user->is_active;
        } else {
            $this->userId    = null;
            $this->role      = 'user';
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

        if ($this->userId) {
            $user = User::query()->findOrFail($this->userId);

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);
        } else {
            $data['password'] = bcrypt($data['password']);
            User::query()->create($data);
        }

        $this->close();
        $this->dispatch('notify', type: 'success', message: 'تغییرات ذخیره شد.');
    }

    public function changeStatus(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function delete(int $id): void
    {
        if (auth()->id() === $id) {
            LivewireAlert::title('اخطار')
                ->text('شما نمیتوانید اکانت خودتون را حذف کنید.')
                ->warning()
                ->timer(5000)
                ->show();
            return;
        }

        User::query()->findOrFail($id)->delete();
    }

    public function resetStatus(): void
    {
        $this->status     = '';
        $this->roleFilter = '';
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowed = ['id', 'name', 'username', 'role', 'is_active', 'created_at'];
        if (! in_array($field, $allowed, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($q) use ($s) {
                    $q->where('name', 'like', $s)
                        ->orWhere('username', 'like', $s)
                        ->orWhere('employee_code', 'like', $s)
                        ->orWhere('phone', 'like', $s)
                        ->orWhere('job_title', 'like', $s);
                });
            })
            ->when($this->status !== '', fn($q) => $q->where('is_active', (bool) $this->status))
            ->when($this->roleFilter !== '', fn($q) => $q->where('role', $this->roleFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.user.user-manage');
    }
}
