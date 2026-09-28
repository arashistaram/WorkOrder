<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سفارش کار — مدیریت دستورکار</title>
    @yield('style')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.css') }}">
    @livewireStyles
</head>
<body>

<section id="seed-data" hidden aria-hidden="true">

    <span id="seed-current-user" data-value="الکس مورگان"></span>
    <ul id="seed-default-checklist">
        <li data-value="بازرسی دستگاه و ثبت یافته‌ها"></li>
        <li data-value="جداسازی و قفل‌گذاری تجهیزات"></li>
        <li data-value="تعویض قطعه آسیب‌دیده"></li>
        <li data-value="تست نهایی سیستم"></li>
    </ul>

    <!-- وضعیت‌ها -->
    <ul id="seed-statuses">
        <li data-value="پیش‌نویس"></li>
        <li data-value="باز"></li>
        <li data-value="در حال انجام"></li>
        <li data-value="متوقف"></li>
        <li data-value="تکمیل‌شده"></li>
        <li data-value="لغو شده"></li>
    </ul>

    <!-- اولویت‌ها -->
    <ul id="seed-priorities">
        <li data-value="کم"></li>
        <li data-value="متوسط"></li>
        <li data-value="بالا"></li>
        <li data-value="بحرانی"></li>
    </ul>

    <!-- افراد -->
    <ul id="seed-people">
        <li data-name="الکس مورگان"   data-initials="ا.م" data-tone="blue"></li>
        <li data-name="سارا چن"       data-initials="س.چ" data-tone="green"></li>
        <li data-name="دیگو رامیرز"   data-initials="د.ر" data-tone="amber"></li>
        <li data-name="پریا نایر"     data-initials="پ.ن" data-tone="violet"></li>
        <li data-name="تام بکر"       data-initials="ت.ب" data-tone="slate"></li>
        <li data-name="لنا فیشر"      data-initials="ل.ف" data-tone="rose"></li>
        <li data-name="مارکوس وب"     data-initials="م.و" data-tone="teal"></li>
    </ul>

    <!-- مشتریان -->
    <ul id="seed-customers">
        <li data-value="صنایع پارس"></li>
        <li data-value="لجستیک آریا"></li>
        <li data-value="تولیدی البرز"></li>
        <li data-value="سلامت مهر"></li>
        <li data-value="گروه رفاه"></li>
        <li data-value="انرژی دماوند"></li>
        <li data-value="فولاد سپاهان"></li>
        <li data-value="املاک کیان"></li>
        <li data-value="دیتاسنتر پارس"></li>
        <li data-value="مجتمع آموزشی پیشرو"></li>
    </ul>

    <!-- پروژه‌ها -->
    <ul id="seed-projects">
        <li data-value="نگهداری تأسیسات"></li>
        <li data-value="برنامه بازسازی تهویه"></li>
        <li data-value="نگهداری پیشگیرانه فصلی"></li>
        <li data-value="راه‌اندازی خط ۴"></li>
        <li data-value="توسعه انبار"></li>
        <li data-value="تعمیرات اضطراری"></li>
        <li data-value="دوره بازرسی سالانه"></li>
        <li data-value="تعویض چیلر"></li>
    </ul>

    <!-- مکان‌ها -->
    <ul id="seed-locations">
        <li data-value="ساختمان A — پشت‌بام"></li>
        <li data-value="کارخانه ۲ — اتاق تأسیسات"></li>
        <li data-value="انبار B — غرفه ۴"></li>
        <li data-value="ساختمان C — زیرزمین"></li>
        <li data-value="خط ۴ — میان‌طبقه"></li>
        <li data-value="بارانداز ۳ — محوطه"></li>
        <li data-value="سالن داده ۲ — ردیف CRAC"></li>
        <li data-value="بال شمالی — طبقه ۳"></li>
    </ul>

    <!-- دارایی‌ها -->
    <ul id="seed-assets">
        <li data-value="HVAC-2201"></li>
        <li data-value="AHU-14"></li>
        <li data-value="VFD-3307"></li>
        <li data-value="CHL-02"></li>
        <li data-value="GEN-01"></li>
        <li data-value="PMP-118"></li>
        <li data-value="CT-05"></li>
        <li data-value="MDP-2"></li>
        <li data-value="CRAH-09"></li>
    </ul>

    <!-- دسته‌بندی‌ها -->
    <ul id="seed-categories">
        <li data-value="تهویه"></li>
        <li data-value="برق"></li>
        <li data-value="مکانیک"></li>
        <li data-value="لوله‌کشی"></li>
        <li data-value="ایمنی"></li>
        <li data-value="پیشگیرانه"></li>
    </ul>

    <!-- ==========================================================
       دستورکارها
       ========================================================== -->
    <div id="seed-orders">

        <article class="seed-order"
                 data-id="WO-10482"
                 data-customer="صنایع پارس"
                 data-project="نگهداری تأسیسات"
                 data-assignee="الکس مورگان"
                 data-priority="بالا"
                 data-status="در حال انجام"
                 data-due-in="2"
                 data-created-ago="3"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="HVAC-2201"
                 data-category="تهویه"
                 data-estimated-hours="6"
                 data-tags="ایمنی|زمان‌بندی‌شده">
            <h3 class="seed-title">تعویض کمپرسور تهویه</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
                <li data-name="compressor-photo.jpg" data-size="1.8 MB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="12"></li>
                <li data-user="سارا چن" data-action="یک پیوست اضافه کرد" data-meta="maintenance-report.pdf" data-minutes="64"></li>
                <li data-user="سیستم" data-action="دستورکار به الکس مورگان تخصیص یافت" data-minutes="210"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10481"
                 data-customer="لجستیک آریا"
                 data-project="دوره بازرسی سالانه"
                 data-assignee="سارا چن"
                 data-priority="بحرانی"
                 data-status="باز"
                 data-due-in="0"
                 data-created-ago="3"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="AHU-14"
                 data-category="پیشگیرانه"
                 data-estimated-hours="4"
                 data-tags="">
            <h3 class="seed-title">بازرسی فن‌های پشت‌بام</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="site-access-notes.pdf" data-size="340 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="سارا چن" data-action="وضعیت را به «باز» تغییر داد" data-minutes="22"></li>
                <li data-user="دیگو رامیرز" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="بازرسی دستگاه و ثبت یافته‌ها" data-minutes="400"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="2600"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10480"
                 data-customer="تولیدی البرز"
                 data-project="راه‌اندازی خط ۴"
                 data-assignee="دیگو رامیرز"
                 data-priority="بالا"
                 data-status="در حال انجام"
                 data-due-in="-3"
                 data-created-ago="5"
                 data-location="خط ۴ — میان‌طبقه"
                 data-asset="VFD-3307"
                 data-category="برق"
                 data-estimated-hours="8"
                 data-tags="گارانتی">
            <h3 class="seed-title">کالیبراسیون سنسورهای فشار — خط ۴</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="به‌روزرسانی سابقه دارایی" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="دیگو رامیرز" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="35"></li>
                <li data-user="پریا نایر" data-action="یک نظر اضافه کرد" data-meta="قطعات به سایت رسید. زمان‌بندی برای صبح پنجشنبه." data-minutes="1420"></li>
                <li data-user="سیستم" data-action="دستورکار به دیگو رامیرز تخصیص یافت" data-minutes="300"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="4200"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10479"
                 data-customer="گروه رفاه"
                 data-project="توسعه انبار"
                 data-assignee="پریا نایر"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="5"
                 data-created-ago="6"
                 data-location="بارانداز ۳ — محوطه"
                 data-asset="PMP-118"
                 data-category="مکانیک"
                 data-estimated-hours="3"
                 data-tags="گارانتی">
            <h3 class="seed-title">تعمیر درب بارانداز شماره ۳</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست نهایی سیستم" data-done="true"></li>
                <li data-label="عکس‌برداری از کار تکمیل‌شده" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="پریا نایر" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="18"></li>
                <li data-user="تام بکر" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست نهایی سیستم" data-minutes="260"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="5400"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10478"
                 data-customer="سلامت مهر"
                 data-project="نگهداری پیشگیرانه فصلی"
                 data-assignee="تام بکر"
                 data-priority="متوسط"
                 data-status="باز"
                 data-due-in="1"
                 data-created-ago="2"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="MDP-2"
                 data-category="ایمنی"
                 data-estimated-hours="5"
                 data-tags="ایمنی">
            <h3 class="seed-title">بازرسی فصلی سیستم اطفای حریق</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="به‌روزرسانی سابقه دارایی" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="تام بکر" data-action="وضعیت را به «باز» تغییر داد" data-minutes="27"></li>
                <li data-user="سیستم" data-action="دستورکار به تام بکر تخصیص یافت" data-minutes="190"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="2400"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10477"
                 data-customer="فولاد سپاهان"
                 data-project="تعمیرات اضطراری"
                 data-assignee="لنا فیشر"
                 data-priority="بحرانی"
                 data-status="در حال انجام"
                 data-due-in="9"
                 data-created-ago="4"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="VFD-3307"
                 data-category="برق"
                 data-estimated-hours="9"
                 data-tags="گارانتی">
            <h3 class="seed-title">تعویض درایو معیوب موتور نوار نقاله</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="لنا فیشر" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="41"></li>
                <li data-user="مارکوس وب" data-action="یک پیوست اضافه کرد" data-meta="vendor-quote.pdf" data-minutes="320"></li>
                <li data-user="سیستم" data-action="دستورکار به لنا فیشر تخصیص یافت" data-minutes="900"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="3900"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10476"
                 data-customer="انرژی دماوند"
                 data-project="تعویض چیلر"
                 data-assignee="مارکوس وب"
                 data-priority="بالا"
                 data-status="در حال انجام"
                 data-due-in="-1"
                 data-created-ago="7"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="CHL-02"
                 data-category="مکانیک"
                 data-estimated-hours="7"
                 data-tags="">
            <h3 class="seed-title">تعمیر اضطراری نشتی — چیلر ۲</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="compressor-photo.jpg" data-size="1.8 MB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="مارکوس وب" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="16"></li>
                <li data-user="لنا فیشر" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="جداسازی و قفل‌گذاری تجهیزات" data-minutes="520"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="6800"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10475"
                 data-customer="صنایع پارس"
                 data-project="برنامه بازسازی تهویه"
                 data-assignee="سارا چن"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="14"
                 data-created-ago="8"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="AHU-14"
                 data-category="تهویه"
                 data-estimated-hours="4"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">نصب فیلترهای جدید هواساز</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="تأیید موجودی قطعات" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="سارا چن" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="9"></li>
                <li data-user="دیگو رامیرز" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست عملکرد تحت بار" data-minutes="430"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="8100"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10474"
                 data-customer="دیتاسنتر پارس"
                 data-project="نگهداری تأسیسات"
                 data-assignee="تخصیص‌نیافته"
                 data-priority="متوسط"
                 data-status="باز"
                 data-due-in="3"
                 data-created-ago="2"
                 data-location="سالن داده ۲ — ردیف CRAC"
                 data-asset="MDP-2"
                 data-category="برق"
                 data-estimated-hours="3"
                 data-tags="">
            <h3 class="seed-title">اسکن حرارتی تابلو برق</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="false"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="عکس‌برداری از کار تکمیل‌شده" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «باز» تغییر داد" data-minutes="48"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="2200"></li>
                <li data-user="سیستم" data-action="دستورکار در صف تخصیص قرار گرفت" data-minutes="150"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10473"
                 data-customer="لجستیک آریا"
                 data-project="توسعه انبار"
                 data-assignee="دیگو رامیرز"
                 data-priority="کم"
                 data-status="در حال انجام"
                 data-due-in="-6"
                 data-created-ago="6"
                 data-location="انبار B — غرفه ۴"
                 data-asset="MDP-2"
                 data-category="برق"
                 data-estimated-hours="12"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">ارتقای روشنایی به LED — انبار B</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="دیگو رامیرز" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="30"></li>
                <li data-user="تام بکر" data-action="یک نظر اضافه کرد" data-meta="نیمی از چراغ‌های غرفه ۴ نصب شد." data-minutes="900"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="6400"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10472"
                 data-customer="سلامت مهر"
                 data-project="نگهداری پیشگیرانه فصلی"
                 data-assignee="الکس مورگان"
                 data-priority="بالا"
                 data-status="تکمیل‌شده"
                 data-due-in="4"
                 data-created-ago="9"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="GEN-01"
                 data-category="پیشگیرانه"
                 data-estimated-hours="6"
                 data-tags="ایمنی">
            <h3 class="seed-title">سرویس ژنراتور پشتیبان</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="به‌روزرسانی سابقه دارایی" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
                <li data-name="site-access-notes.pdf" data-size="340 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="14"></li>
                <li data-user="سارا چن" data-action="یک پیوست اضافه کرد" data-meta="maintenance-report.pdf" data-minutes="200"></li>
                <li data-user="پریا نایر" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست عملکرد تحت بار" data-minutes="380"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="9200"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10471"
                 data-customer="گروه رفاه"
                 data-project="نگهداری تأسیسات"
                 data-assignee="پریا نایر"
                 data-priority="متوسط"
                 data-status="باز"
                 data-due-in="7"
                 data-created-ago="3"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="PMP-118"
                 data-category="مکانیک"
                 data-estimated-hours="3"
                 data-tags="">
            <h3 class="seed-title">تعمیر آبگرمکن — ساختمان C</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="پریا نایر" data-action="وضعیت را به «باز» تغییر داد" data-minutes="25"></li>
                <li data-user="سیستم" data-action="دستورکار به پریا نایر تخصیص یافت" data-minutes="260"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="3100"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10470"
                 data-customer="فولاد سپاهان"
                 data-project="نگهداری پیشگیرانه فصلی"
                 data-assignee="تام بکر"
                 data-priority="بالا"
                 data-status="متوقف"
                 data-due-in="12"
                 data-created-ago="10"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="HVAC-2201"
                 data-category="پیشگیرانه"
                 data-estimated-hours="8"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">نگهداری پیشگیرانه: کمپرسورهای هوا</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="تام بکر" data-action="وضعیت را به «متوقف» تغییر داد" data-minutes="20"></li>
                <li data-user="لنا فیشر" data-action="یک نظر اضافه کرد" data-meta="منتظر تأمین قطعه یدکی از انبار مرکزی هستیم." data-minutes="700"></li>
                <li data-user="سیستم" data-action="دستورکار به تام بکر تخصیص یافت" data-minutes="1500"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="9800"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10469"
                 data-customer="املاک کیان"
                 data-project="تعمیرات اضطراری"
                 data-assignee="لنا فیشر"
                 data-priority="کم"
                 data-status="پیش‌نویس"
                 data-due-in="18"
                 data-created-ago="2"
                 data-location="بال شمالی — طبقه ۳"
                 data-asset="PMP-118"
                 data-category="لوله‌کشی"
                 data-estimated-hours="10"
                 data-tags="">
            <h3 class="seed-title">تعویض مسیر لوله آسیب‌دیده</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="false"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="لنا فیشر" data-action="دستورکار را به‌عنوان پیش‌نویس ذخیره کرد" data-minutes="60"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="2900"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10468"
                 data-customer="مجتمع آموزشی پیشرو"
                 data-project="دوره بازرسی سالانه"
                 data-assignee="سارا چن"
                 data-priority="بحرانی"
                 data-status="در حال انجام"
                 data-due-in="1"
                 data-created-ago="4"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="MDP-2"
                 data-category="ایمنی"
                 data-estimated-hours="4"
                 data-tags="ایمنی">
            <h3 class="seed-title">تست سیستم خاموشی اضطراری</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="site-access-notes.pdf" data-size="340 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="سارا چن" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="11"></li>
                <li data-user="مارکوس وب" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست عملکرد تحت بار" data-minutes="340"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="4100"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10467"
                 data-customer="انرژی دماوند"
                 data-project="نگهداری تأسیسات"
                 data-assignee="مارکوس وب"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="6"
                 data-created-ago="11"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="CT-05"
                 data-category="تهویه"
                 data-estimated-hours="5"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">بازرسی و تمیزکاری برج خنک‌کننده</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="عکس‌برداری از کار تکمیل‌شده" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="compressor-photo.jpg" data-size="1.8 MB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="مارکوس وب" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="17"></li>
                <li data-user="الکس مورگان" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تمیزکاری محل کار و جمع‌آوری ضایعات" data-minutes="300"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="11000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10466"
                 data-customer="دیتاسنتر پارس"
                 data-project="راه‌اندازی خط ۴"
                 data-assignee="دیگو رامیرز"
                 data-priority="کم"
                 data-status="باز"
                 data-due-in="10"
                 data-created-ago="3"
                 data-location="سالن داده ۲ — ردیف CRAC"
                 data-asset="CRAH-09"
                 data-category="برق"
                 data-estimated-hours="6"
                 data-tags="">
            <h3 class="seed-title">کالیبراسیون مجدد برنامه اتوماسیون ساختمان</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="دیگو رامیرز" data-action="وضعیت را به «باز» تغییر داد" data-minutes="44"></li>
                <li data-user="سیستم" data-action="دستورکار به دیگو رامیرز تخصیص یافت" data-minutes="240"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="3300"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10465"
                 data-customer="املاک کیان"
                 data-project="تعمیرات اضطراری"
                 data-assignee="پریا نایر"
                 data-priority="کم"
                 data-status="لغو شده"
                 data-due-in="15"
                 data-created-ago="12"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="PMP-118"
                 data-category="لوله‌کشی"
                 data-estimated-hours="7"
                 data-tags="">
            <h3 class="seed-title">تعویض بخش زنگ‌زده لوله</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="پریا نایر" data-action="وضعیت را به «لغو شده» تغییر داد" data-minutes="26"></li>
                <li data-user="الکس مورگان" data-action="یک نظر اضافه کرد" data-meta="مشتری درخواست لغو داد؛ کار به فصل بعد موکول شد." data-minutes="620"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="12000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10464"
                 data-customer="صنایع پارس"
                 data-project="دوره بازرسی سالانه"
                 data-assignee="تخصیص‌نیافته"
                 data-priority="بالا"
                 data-status="در حال انجام"
                 data-due-in="2"
                 data-created-ago="5"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="PMP-118"
                 data-category="ایمنی"
                 data-estimated-hours="3"
                 data-tags="ایمنی|زمان‌بندی‌شده">
            <h3 class="seed-title">گواهی سالانه شیر یک‌طرفه</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="به‌روزرسانی سابقه دارایی" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="site-access-notes.pdf" data-size="340 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="33"></li>
                <li data-user="سیستم" data-action="دستورکار در صف تخصیص قرار گرفت" data-minutes="120"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="4400"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10463"
                 data-customer="تولیدی البرز"
                 data-project="نگهداری تأسیسات"
                 data-assignee="تام بکر"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="8"
                 data-created-ago="13"
                 data-location="خط ۴ — میان‌طبقه"
                 data-asset="MDP-2"
                 data-category="برق"
                 data-estimated-hours="5"
                 data-tags="گارانتی">
            <h3 class="seed-title">بررسی قطعی برق متناوب</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="تست نهایی سیستم" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="تام بکر" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="13"></li>
                <li data-user="لنا فیشر" data-action="یک پیوست اضافه کرد" data-meta="wiring-diagram.png" data-minutes="260"></li>
                <li data-user="دیگو رامیرز" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست نهایی سیستم" data-minutes="420"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="13000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10462"
                 data-customer="فولاد سپاهان"
                 data-project="دوره بازرسی سالانه"
                 data-assignee="لنا فیشر"
                 data-priority="متوسط"
                 data-status="باز"
                 data-due-in="5"
                 data-created-ago="4"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="GEN-01"
                 data-category="مکانیک"
                 data-estimated-hours="6"
                 data-tags="ایمنی">
            <h3 class="seed-title">سرویس جرثقیل سقفی</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="عکس‌برداری از کار تکمیل‌شده" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="لنا فیشر" data-action="وضعیت را به «باز» تغییر داد" data-minutes="29"></li>
                <li data-user="سیستم" data-action="دستورکار به لنا فیشر تخصیص یافت" data-minutes="330"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="3800"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10461"
                 data-customer="فولاد سپاهان"
                 data-project="تعمیرات اضطراری"
                 data-assignee="مارکوس وب"
                 data-priority="بحرانی"
                 data-status="متوقف"
                 data-due-in="-4"
                 data-created-ago="8"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="HVAC-2201"
                 data-category="مکانیک"
                 data-estimated-hours="11"
                 data-tags="">
            <h3 class="seed-title">آب‌بندی نفوذی سقف — کارخانه ۲</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="false"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="compressor-photo.jpg" data-size="1.8 MB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="مارکوس وب" data-action="وضعیت را به «متوقف» تغییر داد" data-minutes="23"></li>
                <li data-user="سارا چن" data-action="یک نظر اضافه کرد" data-meta="بارندگی ادامه دارد؛ کار تا هفته آینده متوقف می‌ماند." data-minutes="540"></li>
                <li data-user="سیستم" data-action="دستورکار به مارکوس وب تخصیص یافت" data-minutes="1200"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="7200"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10460"
                 data-customer="لجستیک آریا"
                 data-project="برنامه بازسازی تهویه"
                 data-assignee="الکس مورگان"
                 data-priority="کم"
                 data-status="در حال انجام"
                 data-due-in="11"
                 data-created-ago="6"
                 data-location="انبار B — غرفه ۴"
                 data-asset="AHU-14"
                 data-category="تهویه"
                 data-estimated-hours="4"
                 data-tags="">
            <h3 class="seed-title">تعویض المنت خشک‌کن هوا</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="19"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="5800"></li>
                <li data-user="سیستم" data-action="دستورکار به الکس مورگان تخصیص یافت" data-minutes="400"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10459"
                 data-customer="مجتمع آموزشی پیشرو"
                 data-project="دوره بازرسی سالانه"
                 data-assignee="سارا چن"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="3"
                 data-created-ago="14"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="MDP-2"
                 data-category="ایمنی"
                 data-estimated-hours="4"
                 data-tags="ایمنی">
            <h3 class="seed-title">بررسی مدارهای روشنایی اضطراری</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="به‌روزرسانی سابقه دارایی" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="سارا چن" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="10"></li>
                <li data-user="تام بکر" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="به‌روزرسانی سابقه دارایی" data-minutes="460"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="14000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10458"
                 data-customer="دیتاسنتر پارس"
                 data-project="برنامه بازسازی تهویه"
                 data-assignee="دیگو رامیرز"
                 data-priority="بالا"
                 data-status="باز"
                 data-due-in="9"
                 data-created-ago="5"
                 data-location="سالن داده ۲ — ردیف CRAC"
                 data-asset="CRAH-09"
                 data-category="تهویه"
                 data-estimated-hours="6"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">تعویض کمپرسور تهویه</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="false"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="دیگو رامیرز" data-action="وضعیت را به «باز» تغییر داد" data-minutes="37"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="4700"></li>
                <li data-user="سیستم" data-action="دستورکار به دیگو رامیرز تخصیص یافت" data-minutes="280"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10457"
                 data-customer="گروه رفاه"
                 data-project="نگهداری پیشگیرانه فصلی"
                 data-assignee="پریا نایر"
                 data-priority="کم"
                 data-status="پیش‌نویس"
                 data-due-in="20"
                 data-created-ago="2"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="AHU-14"
                 data-category="پیشگیرانه"
                 data-estimated-hours="3"
                 data-tags="">
            <h3 class="seed-title">بازرسی فن‌های پشت‌بام</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="false"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="پریا نایر" data-action="دستورکار را به‌عنوان پیش‌نویس ذخیره کرد" data-minutes="52"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="2800"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10456"
                 data-customer="تولیدی البرز"
                 data-project="راه‌اندازی خط ۴"
                 data-assignee="تام بکر"
                 data-priority="متوسط"
                 data-status="در حال انجام"
                 data-due-in="4"
                 data-created-ago="7"
                 data-location="خط ۴ — میان‌طبقه"
                 data-asset="VFD-3307"
                 data-category="برق"
                 data-estimated-hours="5"
                 data-tags="گارانتی">
            <h3 class="seed-title">کالیبراسیون سنسورهای فشار — خط ۴</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="تام بکر" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="24"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="6100"></li>
                <li data-user="سیستم" data-action="دستورکار به تام بکر تخصیص یافت" data-minutes="350"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10455"
                 data-customer="گروه رفاه"
                 data-project="توسعه انبار"
                 data-assignee="لنا فیشر"
                 data-priority="بالا"
                 data-status="تکمیل‌شده"
                 data-due-in="7"
                 data-created-ago="15"
                 data-location="بارانداز ۳ — محوطه"
                 data-asset="PMP-118"
                 data-category="مکانیک"
                 data-estimated-hours="3"
                 data-tags="">
            <h3 class="seed-title">تعمیر درب بارانداز شماره ۳</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست نهایی سیستم" data-done="true"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="لنا فیشر" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="8"></li>
                <li data-user="پریا نایر" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="تست نهایی سیستم" data-minutes="380"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="15000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10454"
                 data-customer="سلامت مهر"
                 data-project="نگهداری پیشگیرانه فصلی"
                 data-assignee="مارکوس وب"
                 data-priority="کم"
                 data-status="باز"
                 data-due-in="13"
                 data-created-ago="4"
                 data-location="ساختمان C — زیرزمین"
                 data-asset="MDP-2"
                 data-category="ایمنی"
                 data-estimated-hours="4"
                 data-tags="ایمنی">
            <h3 class="seed-title">بازرسی فصلی سیستم اطفای حریق</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="مارکوس وب" data-action="وضعیت را به «باز» تغییر داد" data-minutes="42"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="3900"></li>
                <li data-user="سیستم" data-action="دستورکار به مارکوس وب تخصیص یافت" data-minutes="300"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10453"
                 data-customer="فولاد سپاهان"
                 data-project="تعمیرات اضطراری"
                 data-assignee="سارا چن"
                 data-priority="بالا"
                 data-status="در حال انجام"
                 data-due-in="-2"
                 data-created-ago="9"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="VFD-3307"
                 data-category="برق"
                 data-estimated-hours="8"
                 data-tags="گارانتی">
            <h3 class="seed-title">تعویض درایو معیوب موتور نوار نقاله</h3>
            <p class="seed-description">در بازدید صبحگاهی توسط سرپرست سایت گزارش شد. دستگاه به‌صورت کوتاه‌مدت روشن و خاموش می‌شود و حدود دو بار در هر شیفت، اورلود حرارتی را قطع می‌کند. هماهنگی دسترسی با تیم تأسیسات مشتری پیش از شروع کار انجام شود.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="جداسازی و قفل‌گذاری تجهیزات" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست نهایی سیستم" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
                <li data-name="vendor-quote.pdf" data-size="128 KB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="سارا چن" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="15"></li>
                <li data-user="دیگو رامیرز" data-action="یک پیوست اضافه کرد" data-meta="wiring-diagram.png" data-minutes="280"></li>
                <li data-user="سیستم" data-action="دستورکار به سارا چن تخصیص یافت" data-minutes="640"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="8600"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10452"
                 data-customer="انرژی دماوند"
                 data-project="تعویض چیلر"
                 data-assignee="دیگو رامیرز"
                 data-priority="متوسط"
                 data-status="تکمیل‌شده"
                 data-due-in="6"
                 data-created-ago="16"
                 data-location="کارخانه ۲ — اتاق تأسیسات"
                 data-asset="CHL-02"
                 data-category="مکانیک"
                 data-estimated-hours="6"
                 data-tags="">
            <h3 class="seed-title">تعمیر اضطراری نشتی — چیلر ۲</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="true"></li>
                <li data-label="تست عملکرد تحت بار" data-done="true"></li>
                <li data-label="عکس‌برداری از کار تکمیل‌شده" data-done="true"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="compressor-photo.jpg" data-size="1.8 MB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="دیگو رامیرز" data-action="وضعیت را به «تکمیل‌شده» تغییر داد" data-minutes="7"></li>
                <li data-user="مارکوس وب" data-action="یک آیتم چک‌لیست را تکمیل کرد" data-meta="عکس‌برداری از کار تکمیل‌شده" data-minutes="320"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="16000"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10451"
                 data-customer="صنایع پارس"
                 data-project="برنامه بازسازی تهویه"
                 data-assignee="الکس مورگان"
                 data-priority="بحرانی"
                 data-status="باز"
                 data-due-in="1"
                 data-created-ago="6"
                 data-location="ساختمان A — پشت‌بام"
                 data-asset="AHU-14"
                 data-category="تهویه"
                 data-estimated-hours="4"
                 data-tags="زمان‌بندی‌شده">
            <h3 class="seed-title">نصب فیلترهای جدید هواساز</h3>
            <p class="seed-description">مشکل تکرارشونده که در سه تیکت جداگانه در این ماه گزارش شده است. علت اصلی هنوز تأیید نشده — ابتدا یک اسکن تشخیصی کامل انجام دهید و سپس سفارش قطعات جایگزین بدهید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="maintenance-report.pdf" data-size="2.4 MB" data-kind="file"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="الکس مورگان" data-action="وضعیت را به «باز» تغییر داد" data-minutes="31"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="5500"></li>
                <li data-user="سیستم" data-action="دستورکار به الکس مورگان تخصیص یافت" data-minutes="420"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10450"
                 data-customer="دیتاسنتر پارس"
                 data-project="نگهداری تأسیسات"
                 data-assignee="پریا نایر"
                 data-priority="متوسط"
                 data-status="متوقف"
                 data-due-in="16"
                 data-created-ago="11"
                 data-location="سالن داده ۲ — ردیف CRAC"
                 data-asset="MDP-2"
                 data-category="برق"
                 data-estimated-hours="3"
                 data-tags="">
            <h3 class="seed-title">اسکن حرارتی تابلو برق</h3>
            <p class="seed-description">وظیفه نگهداری پیشگیرانه از برنامه سرویس سالانه تولید شده است. قطعات هم‌اکنون روی خودروی سرویس موجودند. پس از پایان کار، همه قرائت‌ها را در سابقه دارایی ثبت کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="false"></li>
                <li data-label="تست عملکرد تحت بار" data-done="false"></li>
                <li data-label="تأیید موجودی قطعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments"></ul>
            <ul class="seed-activity">
                <li data-user="پریا نایر" data-action="وضعیت را به «متوقف» تغییر داد" data-minutes="21"></li>
                <li data-user="تام بکر" data-action="یک نظر اضافه کرد" data-meta="نیاز به هماهنگی با تیم امنیت سالن داده." data-minutes="660"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="10500"></li>
            </ul>
        </article>

        <article class="seed-order"
                 data-id="WO-10449"
                 data-customer="لجستیک آریا"
                 data-project="توسعه انبار"
                 data-assignee="تام بکر"
                 data-priority="کم"
                 data-status="در حال انجام"
                 data-due-in="8"
                 data-created-ago="8"
                 data-location="انبار B — غرفه ۴"
                 data-asset="MDP-2"
                 data-category="برق"
                 data-estimated-hours="10"
                 data-tags="">
            <h3 class="seed-title">ارتقای روشنایی به LED — انبار B</h3>
            <p class="seed-description">ارتقای مشتری. این ناحیه برای خط تولید آن‌ها حیاتی است، بنابراین کار باید خارج از ساعات کاری انجام شود. پنجره توقف را با مدیر حساب هماهنگ کنید.</p>
            <ul class="seed-checklist">
                <li data-label="بازرسی دستگاه و ثبت یافته‌ها" data-done="true"></li>
                <li data-label="بررسی اتصالات برقی" data-done="true"></li>
                <li data-label="تعویض قطعه آسیب‌دیده" data-done="false"></li>
                <li data-label="تمیزکاری محل کار و جمع‌آوری ضایعات" data-done="false"></li>
            </ul>
            <ul class="seed-attachments">
                <li data-name="wiring-diagram.png" data-size="920 KB" data-kind="image"></li>
            </ul>
            <ul class="seed-activity">
                <li data-user="تام بکر" data-action="وضعیت را به «در حال انجام» تغییر داد" data-minutes="28"></li>
                <li data-user="سیستم" data-action="دستورکار ایجاد شد" data-minutes="7400"></li>
                <li data-user="سیستم" data-action="دستورکار به تام بکر تخصیص یافت" data-minutes="380"></li>
            </ul>
        </article>

    </div>
</section>

<div class="app">
    <!-- ==================== SIDEBAR ==================== -->
    @include('livewire.layouts.includes.dashboard._sidebar')

    <div class="sidebar-scrim" id="sidebar-scrim" data-action="toggle-sidebar"></div>

    <div class="main">
        @include('livewire.layouts.includes.dashboard.topbar')
        {{ $slot }}
    </div>
</div>

<div class="menu menu--floating" id="floating-menu" role="menu"></div>
<div id="modal-root"></div>
<div class="toast-root" id="toast-root" aria-live="polite" aria-atomic="false"></div>

@livewireScripts
@yield('script')
<script src="{{ asset('assets/shared/sweetalert2.js') }}"></script>
{{--<script src="{{ asset('assets/dashboard/js/app.js') }}"></script>--}}
</body>
</html>
