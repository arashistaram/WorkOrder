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
        <a href="{{ route('work-orders') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/work-orders') ? 'is-active' : '' }}" data-action="nav" data-view="list" data-nav="list" aria-current="page">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="2" width="8" height="4" rx="1.5"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h4"/></svg>
            دستورکارها
            <livewire:shared.work-order-nav-count :key="'wo-nav-' . auth()->id()" />
        </a>

        <p class="nav-label">مدیریت</p>

        <a href="{{ route('department') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/department') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 21h18"/>
                <path d="M5 21V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v16"/>
                <path d="M15 21V9h2a2 2 0 0 1 2 2v10"/>
                <path d="M9 7h2"/>
                <path d="M9 11h2"/>
                <path d="M9 15h2"/>
                <path d="M17 13h.01"/>
                <path d="M17 17h.01"/>
            </svg>            واحد ها
        </a>

        <a href="{{ route('user-manage') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/user-manage') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            کاربران
        </a>

        <p class="nav-label">بینش‌ها</p>
        <button class="nav-item" data-action="nav" data-view="reports" data-nav="reports">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18"/><rect x="5" y="11" width="3" height="7" rx="1"/><rect x="11" y="7" width="3" height="11" rx="1"/><rect x="17" y="13" width="3" height="5" rx="1"/></svg>
            گزارش‌ها
        </button>

        @if(auth()->user()?->role === 'admin')
            <p class="nav-label">تنطیمات</p>

            <a href="{{ route('work-order-status-manage') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/work-order-status-manage') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                </svg>
                وضعیت ها
            </a>

            <a href="{{ route('work-order-priorities-manage') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/work-order-priorities-manage') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                </svg>
                اولویت ها
            </a>
        @endif
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
                <span class="ws-mark" aria-hidden="true">{{ getInitials(auth()->user()->name) }}</span>
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
        (() => {
            if (window.__wsMenuBound) return;
            window.__wsMenuBound = true;

            const closeAll = (except = null) => {
                document.querySelectorAll('.ws-menu:not([hidden])').forEach((m) => {
                    if (m === except) return;
                    m.setAttribute('hidden', '');
                    m.closest('.workspace-switcher-wrap')
                        ?.querySelector('[aria-expanded]')
                        ?.setAttribute('aria-expanded', 'false');
                });
            };

            document.addEventListener('click', (e) => {
                const toggle = e.target.closest('[data-action="toggle-ws-menu"]');

                if (toggle) {
                    const wrap = toggle.closest('.workspace-switcher-wrap');
                    const menu = wrap?.querySelector('.ws-menu');
                    if (!menu) return;

                    const isOpen = !menu.hasAttribute('hidden');

                    closeAll(menu); // بقیه منوها را ببند

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
                    closeAll();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeAll();
            });
        })();
    </script>
@endsection
