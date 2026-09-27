<div id="view" style="padding: 50px 50px">

    <!-- ==================== Page Head ==================== -->
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">دستورکارها</h1>
            <p class="page-sub">34 دستورکار · 5 معوق · 11 در حال انجام</p>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">
            <button class="btn" data-action="toast"
                    data-toast="خروجی در صف قرار گرفت — 34 دستورکار به‌صورت CSV ایمیل خواهد شد.">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v12"/><path d="m7 11 5 5 5-5"/><path d="M4 21h16"/>
                </svg>
                خروجی
            </button>

            <button class="btn btn--primary" data-action="new-wo">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                دستورکار جدید
            </button>
        </div>
    </div>

    <!-- ==================== Tabs ==================== -->
    <div class="tabs d-flex align-items-center gap-1" role="tablist">
        <button class="tab is-active" data-action="set-view" data-view="all">
            همه دستورکارها <span class="count">34</span>
        </button>
        <button class="tab" data-action="set-view" data-view="mine">
            تخصیصی به من <span class="count">7</span>
        </button>
        <button class="tab" data-action="set-view" data-view="overdue">
            معوق <span class="count">5</span>
        </button>
        <button class="tab" data-action="set-view" data-view="completed">
            تکمیل‌شده <span class="count">14</span>
        </button>
    </div>

    <!-- ==================== Toolbar ==================== -->
    <div class="toolbar d-flex align-items-center flex-wrap gap-2">

        <div class="toolbar-search flex-grow-1">
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                 stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            </svg>
            <input type="search" id="list-search" placeholder="جستجو بر اساس شماره، عنوان، مشتری…"
                   aria-label="جستجوی دستورکارها" autocomplete="off">
        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">
            <select class="select" data-filter="status" aria-label="فیلتر بر اساس وضعیت">
                <option value="all">همه وضعیت‌ها</option>
                <option value="پیش‌نویس">پیش‌نویس</option>
                <option value="باز">باز</option>
                <option value="در حال انجام">در حال انجام</option>
                <option value="متوقف">متوقف</option>
                <option value="تکمیل‌شده">تکمیل‌شده</option>
                <option value="لغو شده">لغو شده</option>
            </select>

            <select class="select" data-filter="priority" aria-label="فیلتر بر اساس اولویت">
                <option value="all">همه اولویت‌ها</option>
                <option value="کم">کم</option>
                <option value="متوسط">متوسط</option>
                <option value="بالا">بالا</option>
                <option value="بحرانی">بحرانی</option>
            </select>

            <select class="select" data-filter="assignee" aria-label="فیلتر بر اساس مسئول">
                <option value="all">همه مسئولان</option>
                <option value="الکس مورگان">الکس مورگان</option>
                <option value="تام بکر">تام بکر</option>
                <option value="تخصیص‌نیافته">تخصیص‌نیافته</option>
                <option value="دیگو رامیرز">دیگو رامیرز</option>
                <option value="سارا چن">سارا چن</option>
                <option value="لنا فیشر">لنا فیشر</option>
                <option value="مارکوس وب">مارکوس وب</option>
                <option value="پریا نایر">پریا نایر</option>
            </select>

            <select class="select" data-filter="customer" aria-label="فیلتر بر اساس مشتری">
                <option value="all">همه مشتریان</option>
                <option value="املاک کیان">املاک کیان</option>
                <option value="انرژی دماوند">انرژی دماوند</option>
                <option value="تولیدی البرز">تولیدی البرز</option>
                <option value="دیتاسنتر پارس">دیتاسنتر پارس</option>
                <option value="سلامت مهر">سلامت مهر</option>
                <option value="صنایع پارس">صنایع پارس</option>
                <option value="فولاد سپاهان">فولاد سپاهان</option>
                <option value="لجستیک آریا">لجستیک آریا</option>
                <option value="مجتمع آموزشی پیشرو">مجتمع آموزشی پیشرو</option>
                <option value="گروه رفاه">گروه رفاه</option>
            </select>

            <select class="select" data-filter="due" aria-label="فیلتر بر اساس سررسید">
                <option value="all">هر سررسیدی</option>
                <option value="overdue">معوق</option>
                <option value="today">سررسید امروز</option>
                <option value="week">سررسید این هفته</option>
                <option value="month">سررسید این ماه</option>
            </select>
        </div>

        <button class="btn btn--ghost btn--sm" data-action="clear-filters" id="clear-filters" disabled>
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
            پاک کردن
        </button>
    </div>

    <!-- ==================== Table Region ==================== -->
    <div id="table-region">
        <div class="table-wrap">
            <div class="table-scroll">

                <table class="table">
                    <thead>
                    <tr>
                        <th class="cell-check">
                            <input type="checkbox" class="checkbox" id="select-all"
                                   data-action="select-all" aria-label="انتخاب همه دستورکارهای این صفحه">
                        </th>

                        <th><button class="th-sort" data-action="sort" data-key="id">شماره
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th><button class="th-sort" data-action="sort" data-key="title">عنوان
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th><button class="th-sort" data-action="sort" data-key="customer">مشتری
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th class="cell-project">پروژه</th>

                        <th><button class="th-sort" data-action="sort" data-key="assignee">مسئول
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th><button class="th-sort" data-action="sort" data-key="priority">اولویت
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th><button class="th-sort" data-action="sort" data-key="status">وضعیت
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th><button class="th-sort is-sorted" data-action="sort" data-key="dueDate">سررسید
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M6 11l6-6 6 6"/></svg>
                            </button></th>

                        <th><button class="th-sort" data-action="sort" data-key="createdAt">ایجاد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button></th>

                        <th class="cell-actions"><span class="sr-only">اقدامات</span></th>
                    </tr>
                    </thead>

                    <tbody>

                    <!-- Row 1 -->
                    <tr data-action="open-wo" data-id="WO-10482">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10482" aria-label="انتخاب WO-10482"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="{{ route('detail-work-orders') }}" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10482">WO-10482</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">کالیبراسیون سنسورهای فشار — خط ۴</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">صنایع پارس</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">نگهداری تأسیسات</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--blue">ا.م</span>
                  <span class="name">الکس مورگان</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--warning">بالا</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--primary"><span class="badge__dot"></span>در حال انجام</span></td>
                        <td class="cell-due cell-date date-today" data-label="سررسید">8 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">2 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10482" aria-label="اقدامات برای WO-10482">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr data-action="open-wo" data-id="WO-10481">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10481" aria-label="انتخاب WO-10481"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10481">WO-10481</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">تعویض کمپرسور تهویه</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">لجستیک آریا</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">برنامه بازسازی تهویه</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--green">س.چ</span>
                  <span class="name">سارا چن</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--danger">بحرانی</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--primary"><span class="badge__dot"></span>در حال انجام</span></td>
                        <td class="cell-due cell-date date-overdue" data-label="سررسید">3 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">2 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10481" aria-label="اقدامات برای WO-10481">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr data-action="open-wo" data-id="WO-10480">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10480" aria-label="انتخاب WO-10480"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10480">WO-10480</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">بازرسی فن‌های پشت‌بام</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">تولیدی البرز</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">نگهداری پیشگیرانه فصلی</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--amber">د.ر</span>
                  <span class="name">دیگو رامیرز</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--info">متوسط</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--info"><span class="badge__dot"></span>باز</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">15 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">3 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10480" aria-label="اقدامات برای WO-10480">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr data-action="open-wo" data-id="WO-10479">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10479" aria-label="انتخاب WO-10479"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10479">WO-10479</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">تعمیر درب بارانداز شماره ۳</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">سلامت مهر</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">راه‌اندازی خط ۴</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--violet">پ.ن</span>
                  <span class="name">پریا نایر</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--neutral">کم</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--primary"><span class="badge__dot"></span>در حال انجام</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">18 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">3 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10479" aria-label="اقدامات برای WO-10479">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr data-action="open-wo" data-id="WO-10478">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10478" aria-label="انتخاب WO-10478"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10478">WO-10478</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">تعمیر اضطراری نشتی — چیلر ۲</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">گروه رفاه</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">تعمیرات اضطراری</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--slate">ت.ب</span>
                  <span class="name">تام بکر</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--danger">بحرانی</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--warning"><span class="badge__dot"></span>متوقف</span></td>
                        <td class="cell-due cell-date date-overdue" data-label="سررسید">2 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">4 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10478" aria-label="اقدامات برای WO-10478">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 6 -->
                    <tr data-action="open-wo" data-id="WO-10477">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10477" aria-label="انتخاب WO-10477"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10477">WO-10477</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">نصب فیلترهای جدید هواساز</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">انرژی دماوند</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">توسعه انبار</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--rose">ل.ف</span>
                  <span class="name">لنا فیشر</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--info">متوسط</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--primary"><span class="badge__dot"></span>در حال انجام</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">20 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">4 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10477" aria-label="اقدامات برای WO-10477">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 7 -->
                    <tr data-action="open-wo" data-id="WO-10476">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10476" aria-label="انتخاب WO-10476"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10476">WO-10476</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">تعویض درایو معیوب موتور نوار نقاله</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">فولاد سپاهان</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">دوره بازرسی سالانه</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--amber">د.ر</span>
                  <span class="name">دیگو رامیرز</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--warning">بالا</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--info"><span class="badge__dot"></span>باز</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">22 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">5 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10476" aria-label="اقدامات برای WO-10476">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 8 -->
                    <tr data-action="open-wo" data-id="WO-10475">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10475" aria-label="انتخاب WO-10475"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10475">WO-10475</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">اسکن حرارتی تابلو برق</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">دیتاسنتر پارس</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">تعویض چیلر</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--empty">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                  </span>
                  <span class="name">تخصیص‌نیافته</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--neutral">کم</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--neutral"><span class="badge__dot"></span>پیش‌نویس</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">25 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">5 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10475" aria-label="اقدامات برای WO-10475">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 9 -->
                    <tr data-action="open-wo" data-id="WO-10474">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10474" aria-label="انتخاب WO-10474"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10474">WO-10474</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">ارتقای روشنایی به LED — انبار B</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">املاک کیان</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">نگهداری تأسیسات</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--teal">م.و</span>
                  <span class="name">مارکوس وب</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--info">متوسط</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--success"><span class="badge__dot"></span>تکمیل‌شده</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">5 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">6 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10474" aria-label="اقدامات برای WO-10474">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Row 10 -->
                    <tr data-action="open-wo" data-id="WO-10473">
                        <td class="cell-check"><input type="checkbox" class="checkbox"
                                                      data-action="toggle-select" data-id="WO-10473" aria-label="انتخاب WO-10473"></td>
                        <td class="cell-wo" data-label="شماره">
                            <a href="#" wire:navigate.hover class="wo-link" data-action="open-wo" data-id="WO-10473">WO-10473</a>
                        </td>
                        <td class="cell-title" data-label="عنوان"><span class="title-text">سرویس ژنراتور پشتیبان</span></td>
                        <td class="cell-customer" data-label="مشتری"><div class="cell-sub" style="color:var(--color-text-2)">صنایع پارس</div></td>
                        <td class="cell-project" data-label="پروژه"><div class="cell-sub">برنامه بازسازی تهویه</div></td>
                        <td class="cell-assignee" data-label="مسئول">
                <span class="avatar-stack">
                  <span class="avatar avatar--sm avatar--violet">پ.ن</span>
                  <span class="name">پریا نایر</span>
                </span>
                        </td>
                        <td class="cell-priority" data-label="اولویت"><span class="badge badge--warning">بالا</span></td>
                        <td class="cell-status" data-label="وضعیت"><span class="badge badge--success"><span class="badge__dot"></span>تکمیل‌شده</span></td>
                        <td class="cell-due cell-date" data-label="سررسید">1 آبان 1404</td>
                        <td class="cell-created cell-date" data-label="ایجاد">6 آبا</td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn--sm" data-action="row-menu" data-id="WO-10473" aria-label="اقدامات برای WO-10473">
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="5" cy="12" r="1.4"/><circle cx="12" cy="12" r="1.4"/><circle cx="19" cy="12" r="1.4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    </tbody>
                </table>

            </div>

            <!-- ==================== Pagination ==================== -->
            <div class="pagination d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="pagination-info">نمایش <b>1–10</b> از <b>34</b> دستورکار</div>
                <div class="pagination-controls d-flex align-items-center gap-1">
                    <button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="0" disabled aria-label="صفحه قبل">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
                    </button>
                    <button class="page-btn is-active" data-action="page" data-page="1">1</button>
                    <button class="page-btn" data-action="page" data-page="2">2</button>
                    <button class="page-btn" data-action="page" data-page="3">3</button>
                    <span class="page-ellipsis">…</span>
                    <button class="page-btn" data-action="page" data-page="4">4</button>
                    <button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="2" aria-label="صفحه بعد">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>
