<header class="topbar"
        x-data="{ sidebarOpen: false }"
        @sidebar-toggle.window="sidebarOpen = !sidebarOpen">
    <button class="icon-btn sidebar-toggle" data-action="toggle-sidebar" aria-label="باز کردن ناوبری">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>

    <nav class="breadcrumb" id="breadcrumb" aria-label="مسیر ناوبری"></nav>

    <div class="topbar-spacer"></div>

    <button type="button"
            class="icon-btn icon-btn--sm topbar-hamburger"
            data-action="toggle-sidebar"
            aria-label="باز کردن ناوبری"
            aria-controls="sidebar"
            aria-expanded="false">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 6h18M3 12h18M3 18h18"/>
        </svg>
    </button>


</header>

