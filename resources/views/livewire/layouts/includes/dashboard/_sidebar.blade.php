<aside class="sidebar" id="sidebar" aria-label="ناوبری اصلی">
    <div class="sidebar-brand">
        <span class="brand-mark" aria-hidden="true">ایسترم</span>
        <span class="brand-name">سفارش کار</span>
        <button class="icon-btn icon-btn--sm sidebar-toggle" data-action="toggle-sidebar" aria-label="بستن ناوبری" style="display:none" id="sidebar-close"></button>
    </div>

    <nav class="sidebar-nav">
        <p class="nav-label">عملیات</p>
        <a href="{{ route('dashboard') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard') ? 'is-active' : '' }}" data-action="nav" data-view="dashboard" data-nav="dashboard">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            داشبورد
        </a>
        <a href="{{ route('work-orders') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('work-orders') ? 'is-active' : '' }}" data-action="nav" data-view="list" data-nav="list" aria-current="page">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="2" width="8" height="4" rx="1.5"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h4"/></svg>
            دستورکارها
            <span class="nav-count" id="nav-wo-count">0</span>
        </a>

        <p class="nav-label">سوابق</p>
        <button class="nav-item" data-action="nav" data-view="customers" data-nav="customers">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 21V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v15"/><path d="M16 10h2a2 2 0 0 1 2 2v9"/><path d="M2 21h20"/><path d="M8 8h4M8 12h4M8 16h4"/></svg>
            مشتریان
        </button>
        <button class="nav-item" data-action="nav" data-view="projects" data-nav="projects">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h3.6l1.8 2H19a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            پروژه‌ها
        </button>
        <button class="nav-item" data-action="nav" data-view="team" data-nav="team">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            تیم
        </button>
        <button class="nav-item" data-action="nav" data-view="assets" data-nav="assets">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8v8a2 2 0 0 1-1 1.73l-7 4a2 2 0 0 1-2 0l-7-4A2 2 0 0 1 3 16V8a2 2 0 0 1 1-1.73l7-4a2 2 0 0 1 2 0l7 4A2 2 0 0 1 21 8z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>
            دارایی‌ها
        </button>

        <p class="nav-label">بینش‌ها</p>
        <button class="nav-item" data-action="nav" data-view="reports" data-nav="reports">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18"/><rect x="5" y="11" width="3" height="7" rx="1"/><rect x="11" y="7" width="3" height="11" rx="1"/><rect x="17" y="13" width="3" height="5" rx="1"/></svg>
            گزارش‌ها
        </button>
        <button class="nav-item" data-action="nav" data-view="settings" data-nav="settings">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            تنظیمات
        </button>
    </nav>

    <div class="sidebar-footer">

        <div class="workspace-switcher-wrap">
            <button
                type="button"
                class="workspace-switcher"
                data-action="toggle-ws-menu"
                aria-haspopup="true"
                aria-expanded="false"
            >
                <span class="ws-mark" aria-hidden="true">آص</span>
                <span class="ws-text">
                <span class="ws-name">{{ auth()->user()->name }}</span>
                <span class="ws-plan">{{ auth()->user()->job_title }}</span>
            </span>
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            <div class="ws-menu" hidden>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ws-menu__item ws-menu__item--danger">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <path d="m16 17 5-5-5-5"/>
                            <path d="M21 12H9"/>
                        </svg>
                        <span>خروج از حساب</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

@section('script')
    <script>
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('[data-action="toggle-ws-menu"]');

            if (toggle) {
                e.stopPropagation();
                const wrap = toggle.closest('.workspace-switcher-wrap');
                const menu = wrap.querySelector('.ws-menu');
                const isOpen = !menu.hasAttribute('hidden');

                document.querySelectorAll('.ws-menu:not([hidden])').forEach(m => {
                    if (m !== menu) {
                        m.setAttribute('hidden', '');
                        m.closest('.workspace-switcher-wrap')
                            ?.querySelector('[aria-expanded]')
                            ?.setAttribute('aria-expanded', 'false');
                    }
                });

                if (isOpen) {
                    menu.setAttribute('hidden', '');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    menu.removeAttribute('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
                return;
            }

            if (!e.target.closest('.ws-menu')) {
                document.querySelectorAll('.ws-menu:not([hidden])').forEach(m => {
                    m.setAttribute('hidden', '');
                    m.closest('.workspace-switcher-wrap')
                        ?.querySelector('[aria-expanded]')
                        ?.setAttribute('aria-expanded', 'false');
                });
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            document.querySelectorAll('.ws-menu:not([hidden])').forEach(m => {
                m.setAttribute('hidden', '');
                m.closest('.workspace-switcher-wrap')
                    ?.querySelector('[aria-expanded]')
                    ?.setAttribute('false');
            });
        });
    </script>
@endsection
