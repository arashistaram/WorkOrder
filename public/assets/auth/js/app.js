(function () {
    'use strict';

    const loginForm   = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const title       = document.getElementById('title');
    const subtitle    = document.getElementById('subtitle');
    const footerText  = document.getElementById('footerText');
    const switchLink  = document.getElementById('switchLink');

    let mode = 'login';

    function setMode(next) {
        mode = next;

        if (mode === 'login') {
            title.textContent      = 'ورود به حساب';
            subtitle.textContent   = 'خوش آمدی. برای ادامه، اطلاعاتت را وارد کن.';
            footerText.textContent = 'حساب کاربری نداری؟';
            switchLink.textContent = 'ثبت‌نام کن';
            loginForm.classList.add('is-active');
            registerForm.classList.remove('is-active');
        } else {
            title.textContent      = 'ساخت حساب جدید';
            subtitle.textContent   = 'در چند ثانیه به ما بپیوند.';
            footerText.textContent = 'قبلاً ثبت‌نام کرده‌ای؟';
            switchLink.textContent = 'وارد شو';
            registerForm.classList.add('is-active');
            loginForm.classList.remove('is-active');
        }
    }

    switchLink.addEventListener('click', function (e) {
        e.preventDefault();
        setMode(mode === 'login' ? 'register' : 'login');
    });

    function validate(form) {
        let ok = true;
        const fields = form.querySelectorAll('input[required]');

        fields.forEach(function (field) {
            field.style.borderColor = '';
            const value = field.value.trim();

            if (!value) {
                field.style.borderColor = '#e5484d';
                ok = false;
                return;
            }
            if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                field.style.borderColor = '#e5484d';
                ok = false;
            }
            if (field.type === 'password' && value.length < 8) {
                field.style.borderColor = '#e5484d';
                ok = false;
            }
        });

        if (!ok) {
            const first = form.querySelector('input[style*="#e5484d"], input[style*="rgb(229, 72, 77)"]');
            if (first) first.focus();
        }
        return ok;
    }

    function bindSubmit(form, successMessage) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validate(form)) return;

            const btn = form.querySelector('.btn-primary');
            const original = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'در حال ارسال…';
            btn.style.opacity = '.7';

            setTimeout(function () {
                btn.disabled = false;
                btn.textContent = original;
                btn.style.opacity = '';

                console.log(successMessage);
            }, 1000);
        });
    }

    bindSubmit(loginForm, 'ورود موفق — در حال انتقال…');
    bindSubmit(registerForm, 'حساب ساخته شد — در حال انتقال…');

    /* ---------- پاک کردن حالت خطا هنگام تایپ ---------- */
    document.querySelectorAll('.input').forEach(function (input) {
        input.addEventListener('input', function () {
            input.style.borderColor = '';
        });
    });

})();
