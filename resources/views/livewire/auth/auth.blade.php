<div>
    {{-- ==================== هدر ==================== --}}
    <div class="form-header">
        <h1 id="title">
            {{ $mode === 'login' ? 'ورود به حساب' : 'ساخت حساب جدید' }}
        </h1>
        <p id="subtitle">
            {{ $mode === 'login'
                ? 'خوش آمدی. برای ادامه، اطلاعاتت را وارد کن.'
                : 'چند ثانیه وقت بذار و حسابت را بساز.' }}
        </p>
    </div>

    @if ($mode === 'login')

        <form wire:submit="login" class="form is-active" novalidate wire:key="login-form">

            <div class="field">
                <label for="login-username">ایمیل</label>
                <input
                    id="login-username"
                    type="text"
                    wire:model.blur="loginUsername"
                    class="input @error('loginUsername') is-invalid @enderror"
                    placeholder="مثال : arash123"
                    required
                >
                @error('loginUsername') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <div class="label-row">
                    <label for="login-password">رمز عبور</label>
                </div>
                <input
                    id="login-password"
                    type="password"
                    wire:model.blur="loginPassword"
                    class="input @error('loginPassword') is-invalid @enderror"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
                @error('loginPassword') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="login">
                <span wire:loading.remove wire:target="login">ورود</span>
                <span wire:loading wire:target="login">در حال ورود…</span>
            </button>

            @error('oauth') <span class="field-error">{{ $message }}</span> @enderror
        </form>
    @endif

    {{-- =====================================================================
         فرم ثبت‌نام
    ====================================================================== --}}
    @if ($mode === 'register')
        <form wire:submit="register" class="form is-active" novalidate wire:key="register-form">

            <div class="field">
                <label for="reg-name">نام و نام خانوادگی</label>
                <input
                    id="reg-name"
                    type="text"
                    wire:model.blur="regName"
                    class="input @error('regName') is-invalid @enderror"
                    placeholder="مثلاً سارا محمدی"
                    autocomplete="name"
                    required
                >
                @error('regName') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="reg-username">نام کاربری</label>
                <input
                    id="reg-username"
                    type="text"
                    wire:model.blur="regUsername"
                    class="input @error('regUsername') is-invalid @enderror"
                    placeholder="arash123"
                    required
                >
                @error('regEmail') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="reg-password">رمز عبور</label>
                <input
                    id="reg-password"
                    type="password"
                    wire:model.blur="regPassword"
                    class="input @error('regPassword') is-invalid @enderror"
                    placeholder="حداقل ۸ کاراکتر"
                    autocomplete="new-password"
                    required
                >
                @error('regPassword') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="reg-password-confirm">تکرار رمز عبور</label>
                <input
                    id="reg-password-confirm"
                    type="password"
                    wire:model.blur="regPasswordConfirmation"
                    class="input @error('regPasswordConfirmation') is-invalid @enderror"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
                @error('regPasswordConfirmation') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="register">
                <span wire:loading.remove wire:target="register">ساخت حساب</span>
                <span wire:loading wire:target="register">در حال ساخت…</span>
            </button>

            @error('oauth') <span class="field-error">{{ $message }}</span> @enderror
        </form>
    @endif

    <p class="form-footer">
        <span>{{ $mode === 'login' ? 'حساب کاربری نداری؟' : 'قبلاً ثبت‌نام کرده‌ای؟' }}</span>

        <a href="#"
           wire:click.prevent="switchMode('{{ $mode === 'login' ? 'register' : 'login' }}')">
            {{ $mode === 'login' ? 'ثبت‌نام کن' : 'وارد شو' }}
        </a>
    </p>
</div>
