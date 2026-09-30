{{-- resources/views/errors/403.blade.php --}}
    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عدم دسترسی | ۴۰۳</title>
    @vite(['resources/css/app.css'])
    <style>
        @font-face {
            font-family: 'Vazir';
            src: url('/assets/fonts/Vazirmatn-Thin.ttf') format('truetype');
            font-weight: 100;
            font-style: normal;
            font-display: swap;
        }
        body {
            font-family: 'Vazir';
            background-color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 40px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 90%;
        }
        .error-code {
            font-size: 72px;
            font-weight: 700;
            color: #dc2626;
            margin: 0 0 8px;
            line-height: 1;
        }
        .error-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 12px;
        }
        .error-message {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 24px;
            line-height: 1.8;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background-color: #3b82f6;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
<div class="error-container">
    <div class="error-code">۴۰۳</div>
    <h1 class="error-title">دسترسی غیرمجاز</h1>
    <p class="error-message">
        متأسفانه شما مجوز لازم برای دسترسی به این بخش را ندارید.
        در صورت نیاز با مدیر سیستم تماس بگیرید.
    </p>
    <a href="{{ url('/dashboard') }}" class="btn-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        بازگشت به خانه
    </a>
</div>
</body>
</html>
