<?php

namespace App\Livewire;

use App\Models\OutputMessengerAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts._dashboard')]
#[Title('کاربران Output Messenger')]
class OutputMessengerManageController extends Component
{
    use WithPagination;


    public string $userSearch = '';
    public ?string $selectedUserName = null;

    public string $search = '';
    public string $status = '';

    public bool $showModal = false;
    public ?int $accountId = null;

    public ?int $user_id = null;
    public string $output_user_id  = '';
    public string $output_username = '';
    public string $output_email    = '';
    public string $output_mobile   = '';
    public bool $is_active               = true;
    public bool $notify_on_assign        = true;
    public bool $notify_on_status_change = true;

    protected function rules(): array
    {
        return [
            'user_id' => [
                'required', 'exists:users,id',
                Rule::unique('output_messenger_accounts', 'user_id')->ignore($this->accountId),
            ],
            'output_user_id'  => 'nullable|string|max:100',
            'output_username' => 'nullable|string|max:150',
            'output_email'    => 'nullable|email|max:190',
            'output_mobile'   => 'nullable|string|max:30',
            'is_active'               => 'boolean',
            'notify_on_assign'        => 'boolean',
            'notify_on_status_change' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'user_id.required' => 'کاربر را انتخاب کنید.',
            'user_id.unique'   => 'این کاربر قبلاً حساب Output دارد.',
            'output_email.email' => 'ایمیل معتبر نیست.',
        ];
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    public function open(?int $id = null): void
    {
        $this->resetValidation();
        $this->reset([
            'user_id', 'output_user_id', 'output_username',
            'output_email', 'output_mobile', 'userSearch', 'selectedUserName',
            'is_active', 'notify_on_assign', 'notify_on_status_change',
        ]);

        if ($id) {
            $a = OutputMessengerAccount::query()->with('user')->findOrFail($id);

            $this->accountId              = $a->id;
            $this->user_id                = $a->user_id;
            $this->selectedUserName       = $a->user?->name ?? $a->user?->username;
            $this->output_user_id         = $a->output_user_id ?? '';
            $this->output_username        = $a->output_username ?? '';
            $this->output_email           = $a->output_email ?? '';
            $this->output_mobile          = $a->output_mobile ?? '';
            $this->is_active              = (bool) $a->is_active;
            $this->notify_on_assign       = (bool) $a->notify_on_assign;
            $this->notify_on_status_change = (bool) $a->notify_on_status_change;
        } else {
            $this->accountId               = null;
            $this->is_active               = true;
            $this->notify_on_assign        = true;
            $this->notify_on_status_change = true;
        }

        $this->showModal = true;
    }

    public function selectUser(int $id): void
    {
        $user = User::query()->find($id);
        if (! $user) return;

        $this->user_id          = $user->id;
        $this->selectedUserName = $user->name ?? $user->username;
        $this->userSearch       = '';
        $this->resetValidation('user_id');
    }

    public function clearUser(): void
    {
        $this->user_id          = null;
        $this->selectedUserName = null;
        $this->userSearch       = '';
        $this->resetValidation('user_id');
    }

    public function updatedUserSearch(): void {}

    public function close(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['output_user_id']  = $data['output_user_id'] ?: null;
        $data['output_username'] = $data['output_username'] ?: null;
        $data['output_email']    = $data['output_email'] ?: null;
        $data['output_mobile']   = $data['output_mobile'] ?: null;

        if ($this->accountId) {
            OutputMessengerAccount::query()->findOrFail($this->accountId)->update($data);
        } else {
            OutputMessengerAccount::query()->create($data);
        }

        $this->close();

        LivewireAlert::title('موفق')
            ->text('حساب Output Messenger ذخیره شد.')
            ->success()->timer(2500)->show();
    }

    public function changeStatus(int $id): void
    {
        $a = OutputMessengerAccount::query()->findOrFail($id);
        $a->update(['is_active' => ! $a->is_active]);
    }

    public function delete(int $id): void
    {
        OutputMessengerAccount::query()->findOrFail($id)->delete();

        LivewireAlert::title('حذف شد')
            ->text('حساب با موفقیت حذف شد.')
            ->success()->timer(2000)->show();
    }

    public function resetFilters(): void
    {
        $this->status = '';
        $this->resetPage();
    }

    #[Computed]
    public function accounts(): LengthAwarePaginator
    {
        return OutputMessengerAccount::query()
            ->with('user')
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($qq) use ($s) {
                    $qq->where('output_user_id', 'like', $s)
                        ->orWhere('output_username', 'like', $s)
                        ->orWhere('output_email', 'like', $s)
                        ->orWhere('output_mobile', 'like', $s)
                        ->orWhereHas('user', fn($u) => $u
                            ->where('name', 'like', $s)
                            ->orWhere('username', 'like', $s));
                });
            })
            ->when($this->status !== '', fn($q) => $q->where('is_active', (bool) $this->status))
            ->orderByDesc('id')
            ->paginate(15);
    }


    #[Computed]
    public function searchableUsers(): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->when($this->accountId, function ($q) {
                $q->where(function ($qq) {
                    $qq->whereDoesntHave('outputAccount')
                        ->orWhere('id', $this->user_id);
                });
            }, function ($q) {
                $q->whereDoesntHave('outputAccount');
            })
            ->when($this->userSearch !== '', function ($q) {
                $s = '%' . $this->userSearch . '%';
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', $s)
                        ->orWhere('username', 'like', $s)
                        ->orWhere('employee_code', 'like', $s)
                        ->orWhere('phone', 'like', $s);
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'username', 'employee_code']);
    }

    public function render()
    {
        return view('livewire.output-messenger-manage');
    }
}
