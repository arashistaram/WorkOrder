<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ورود / ثبت‌نام' }} | سیستم سفارش کار</title>
    <link rel="stylesheet" href="{{ asset('assets/auth/css/app.css') }}">
    @livewireStyles

</head>
<body>

<div class="layout">

    <main class="form-side">
        <div class="form-wrap">

            <!-- لوگو -->
            <div class="brand">
                <div class="brand-mark">ایسترم</div>
                <span class="brand-name">سفارش کار</span>
            </div>

            {{ $slot }}

        </div>
    </main>

    <!-- ============ ستون بصری ============ -->
    <aside class="visual-side">
        <div class="statement">
            <p>
                ابزاری ساده<br>
                برای کارهای <em>پیچیده</em>.
            </p>
        </div>

        <div class="visual-footer">
            <span><span class="dot"></span>در حال ساخت نسخهٔ هوش مصنوعی</span>
            <span>© سفارش کار 1405</span>
        </div>
    </aside>

</div>

@livewireScripts
<script src="{{asset('assets/shared/sweetalert2.js')}}"></script>
</body>
</html>
