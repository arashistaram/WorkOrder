<aside class="sidebar" id="sidebar" aria-label="ناوبری اصلی">
    <div class="sidebar-brand">
        <span class="brand-mark" aria-hidden="true">ایسترم</span>
        <span class="brand-name">سفارش کار</span>
        <button type="button"
                class="icon-btn icon-btn--sm sidebar-toggle"
                data-action="toggle-sidebar"
                aria-label="بستن ناوبری"
                id="sidebar-close">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
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

        @if(auth()->user()->role === 'admin')
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
        @endif
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('user-manage') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/user-manage') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                کاربران
            </a>
        @endif
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

        @if(auth()->user()?->role === 'manager')
            <a href="{{ route('output-messenger-manage') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/output-messenger-manage') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                </svg>
                Output Messenger
            </a>

            <a href="{{ route('work-orders.substitutes') }}" wire:navigate.hover class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/work-orders.substitutes') ? 'is-active' : '' }}" data-action="nav" data-view="team" data-nav="team">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                جانشین‌های من
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'manager']) || auth()->user()->isPrivileged())
            <a href="{{ route('work-orders.approvals') }}" wire:navigate.hover
               class="nav-item {{ request()->is('dashboard/work-orders/approvals') ? 'is-active' : '' }}">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                تایید سفارش‌ها
                @php
                    $pendingCount = \App\Models\WorkOrder::query()
                        ->where('approval_status', 0)
                        ->where('approval_stage', '!=', 'pending_admin')
                        ->when(auth()->user()->role === 'manager', function ($q) {
                            $deptIds = auth()->user()->managedDepartments()->pluck('departments.id');
                            $q->whereIn('department_id', $deptIds);
                        })
                        ->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="nav-count">{{ $pendingCount }}</span>
                @endif
            </a>
        @endif

        @if(auth()->user()->role === 'admin')
        <p class="nav-label">هوشمند</p>

            <a href="{{ route('ai-assistant') }}" wire:navigate.hover
               class="nav-item {{ request()->is('dashboard/ai-assistant') ? 'is-active' : '' }}">
                🤖 دستیار هوشمند
            </a>
        @endif

    </nav>

    <div class="sidebar-footer">

        <div class="workspace-switcher-wrap"
             x-data="{ open: false }"
             @click.outside="open = false"
             @keydown.escape.window="open = false">

            <button
                type="button"
                class="workspace-switcher"
                @click="open = !open"
                :aria-expanded="open"
                aria-haspopup="true"
            >
            <span class="ws-mark" aria-hidden="true">
                {{ getInitials(auth()->user()->name) }}
            </span>
                <span class="ws-text">
                <span class="ws-name">{{ auth()->user()->name }}</span>
                <span class="ws-plan">{{ auth()->user()->job_title }}</span>
            </span>
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true"
                     :style="open ? 'transform: rotate(180deg)' : ''">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            <div class="ws-menu"
                 x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1">

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
            const getSidebar   = () => document.getElementById('sidebar');
            const getBackdrop  = () => document.getElementById('sidebar-backdrop');
            const getHamburger = () => document.querySelector('.topbar-hamburger');

            const open = () => {
                const sidebar = getSidebar();
                if (!sidebar) return;
                sidebar.classList.add('is-open');
                getBackdrop()?.classList.add('is-visible');
                document.body.classList.add('sidebar-open');
                getHamburger()?.setAttribute('aria-expanded', 'true');
            };

            const close = () => {
                const sidebar = getSidebar();
                if (!sidebar) return;
                sidebar.classList.remove('is-open');
                getBackdrop()?.classList.remove('is-visible');
                document.body.classList.remove('sidebar-open');
                getHamburger()?.setAttribute('aria-expanded', 'false');
            };

            const toggle = () => {
                const sidebar = getSidebar();
                if (!sidebar) return;
                sidebar.classList.contains('is-open') ? close() : open();
            };

            if (!window.__sidebarBound) {
                window.__sidebarBound = true;

                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-action="toggle-sidebar"]');
                    if (btn) {
                        e.preventDefault();
                        toggle();
                        return;
                    }
                    if (e.target.closest('#sidebar-backdrop')) {
                        close();
                        return;
                    }
                    const link = e.target.closest('#sidebar a.nav-item');
                    if (link && window.innerWidth < 1024) close();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) close();
                });
            }

            document.addEventListener('livewire:navigated', close);
            document.addEventListener('livewire:navigate', close);
        })();
    </script>
@endsection
