<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._auth')]
#[Title('ورود / ثبت‌نام')]
final class AuthController extends Component
{
    public string $mode = 'login';

//    Login Properties
    public string $loginUsername = '';
    public string $loginPassword = '';
    public bool   $remember = true;


//    Register Properties
    public string $regName = '';
    public string $regUsername = '';
    public string $regPassword = '';
    public string $regPasswordConfirmation = '';

    protected function rules(): array
    {
        return match ($this->mode) {
            'login' => [
                'loginUsername'  => ['required', 'string'],
                'loginPassword'  => ['required', 'string'],
            ],
            'register' => [
                'regName'     => ['required', 'string', 'max:100'],
                'regUsername'    => ['required', 'string', 'max:150', 'unique:users,username'],
                'regPassword' => ['required', 'string', Password::min(8)],
            ],
            default => [],
        };
    }

    protected function messages(): array
    {
        return [
            'required' => 'فیلد :attribute الزامی است.',
            'username'    => 'نام کاربری وارد شده معتبر نیست.',
            'unique'   => 'این ایمیل قبلاً ثبت شده است.',
            'min'      => ':attribute باید حداقل :min کاراکتر باشد.',
            'max'      => ':attribute نباید بیشتر از :max کاراکتر باشد.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'loginUsername' => 'نام کاربری',
            'loginPassword' => 'رمز عبور',
            'regName'       => 'نام و نام خانوادگی',
            'regUsername'   => 'نام کاربری',
            'regPassword'   => 'رمز عبور',
        ];
    }
    public function switchMode(string $mode): void
    {
        if (! in_array($mode, ['login', 'register'], true)) {
            return;
        }

        $this->mode = $mode;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        $credentials = [
            'username'    => $this->loginUsername,
            'password' => $this->loginPassword,
        ];

        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey(), 60);

            $this->addError('loginUsername', 'نام کاربری یا رمز عبور اشتباه است.');
            return;
        }
        Auth::user()->update(['last_login_at' => date('Y-m-d H:i:s')]);
        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function register(): void
    {
        $this->validate();

        if ($this->regPassword !== $this->regPasswordConfirmation) {
            $this->addError('regPasswordConfirmation', 'تکرار رمز عبور مطابقت ندارد.');
            return;
        }

        $user = User::query()->create([
            'name'      => $this->regName,
            'username'  => $this->regUsername,
            'password'  => Hash::make($this->regPassword),
        ]);

        Auth::login($user, true);
        session()->regenerate();

        $this->redirectRoute('dashboard', navigate: true);
    }


    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'loginUsername' => "تعداد تلاش‌های ناموفق زیاد بود. لطفاً {$seconds} ثانیه دیگر تلاش کن.",
        ]);
    }

    protected function throttleKey(): string
    {
        return mb_strtolower($this->loginUsername).'|'.request()->ip();
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.auth.auth');

    }
}
