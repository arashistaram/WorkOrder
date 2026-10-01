<div>
    <div id="view" style="padding: 50px 50px">

        {{-- ==================== Page Head ==================== --}}
        <div class="page-head">
            <div>
                <h1 class="page-title">نمای کلی عملیات</h1>
                <p class="page-sub">{{ $this->subtitle }}</p>
            </div>
            <div class="page-head-actions">
                <a href="{{ route('work-orders') }}" wire:navigate.hover class="btn">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>
                    </svg>
                    مشاهده همه دستورکارها
                </a>
            </div>
        </div>

        {{-- ==================== KPI Grid ==================== --}}
        <section class="kpi-grid" aria-label="شاخص‌های کلیدی">

            {{-- KPI 1: دستورکارهای باز --}}
            <article class="kpi">
                <div class="kpi-top">
                    <span class="kpi-label">دستورکارهای باز</span>
                    <span class="kpi-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                            <path d="M5.5 5h13l3.5 7v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-6z"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">{{ $this->openCount }}</div>
                <div class="kpi-foot">
                    <div class="kpi-meta">
                        @php $d = $this->openDelta; @endphp
                        <span class="delta {{ $d >= 0 ? 'delta--up' : 'delta--down' }}">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                 stroke-linejoin="round">
                                @if($d >= 0)
                                    <path d="M12 19V5M6 11l6-6 6 6"/>
                                @else
                                    <path d="M12 5v14M18 13l-6 6-6-6"/>
                                @endif
                            </svg>
                            {{ $d >= 0 ? '+' : '' }}{{ $d }}%
                        </span>
                        <span class="kpi-sub">نسبت به ۳۰ روز گذشته</span>
                    </div>
                </div>
            </article>

            {{-- KPI 2: در حال انجام --}}
            <article class="kpi">
                <div class="kpi-top">
                    <span class="kpi-label">در حال انجام</span>
                    <span class="kpi-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a9 9 0 1 0 9 9"/>
                            <path d="M12 7.5V12l3 1.8"/>
                            <path d="M18 3v5h5"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">{{ $this->inProgressCount }}</div>
                <div class="kpi-foot">
                    <div class="kpi-meta">
                        <span class="kpi-sub">در حال انجام توسط تیم</span>
                    </div>
                </div>
            </article>

            {{-- KPI 3: سررسید امروز --}}
            <article class="kpi">
                <div class="kpi-top">
                    <span class="kpi-label">سررسید امروز</span>
                    <span class="kpi-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M3 10h18M8 3v4M16 3v4"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">{{ $this->dueTodayCount }}</div>
                <div class="kpi-foot">
                    <div class="kpi-meta">
                        <span class="delta delta--down">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M12 5v14M18 13l-6 6-6-6"/>
                            </svg>
                            {{ $this->overdueCount }} معوق
                        </span>
                        <span class="kpi-sub">نیازمند زمان‌بندی</span>
                    </div>
                </div>
            </article>

            {{-- KPI 4: تکمیل‌شده این ماه --}}
            <article class="kpi">
                <div class="kpi-top">
                    <span class="kpi-label">تکمیل‌شده این ماه</span>
                    <span class="kpi-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m8.4 12.4 2.4 2.4 4.8-5"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">{{ $this->completedThisMonthCount }}</div>
                <div class="kpi-foot">
                    <div class="kpi-meta">
                        <span class="kpi-sub">در ماه جاری</span>
                    </div>
                </div>
            </article>

        </section>

        {{-- ==================== Dash Grid — Attention + Workload ==================== --}}
        <div class="dash-grid">

            {{-- نیازمند توجه --}}
            <section class="card">
                <header class="card-head">
                    <h2>نیازمند توجه</h2>
                    <span class="card-sub">معوق و سررسید تا امروز</span>
                </header>
                <div class="card-body card-body--flush">

                    @forelse($this->attentionItems as $wo)
                        <a href="{{ route('work-orders.detail', $wo->id) }}"
                           wire:navigate.hover
                           class="attention-row"
                           wire:key="attn-{{ $wo->id }}">

                            <div class="attention-main">
                                <div class="attention-title">{{ $wo->title }}</div>
                                <div class="attention-meta">
                                    <span class="mono">{{ $wo->code }}</span>
                                    <span class="dot"></span>
                                    <span>{{ $wo->department?->name ?? '—' }}</span>
                                    <span class="dot"></span>
                                    <span>{{ $wo->assignee?->name ?? 'بدون مسئول' }}</span>
                                </div>
                            </div>

                            @php
                                $daysLeft = $wo->due_date ? (int) today()->diffInDays($wo->due_date, false) : null;
                            @endphp

                            @if($daysLeft !== null && $daysLeft < 0)
                                <span class="badge badge--danger">{{ abs($daysLeft) }} روز معوق</span>
                            @elseif($daysLeft === 0)
                                <span class="badge badge--warning">سررسید امروز</span>
                            @else
                                <span class="badge badge--neutral">سررسید فردا</span>
                            @endif

                            @if($wo->status)
                                <span class="badge {{ $wo->status->badge_class ?? 'badge--info' }}">
                                    <span class="badge__dot"></span>{{ $wo->status->label }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <div style="padding:32px;text-align:center;color:var(--color-text-2);font-size:13px">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.5" style="width:40px;height:40px;opacity:.3">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <p>هیچ موردی نیازمند توجه نیست 🎉</p>
                        </div>
                    @endforelse

                </div>
            </section>

            {{-- بار کاری فعال --}}
            <section class="card">
                <header class="card-head">
                    <h2>بار کاری فعال</h2>
                    <span class="card-sub">موارد باز به تفکیک مسئول</span>
                </header>
                <div class="workload">

                    @forelse($this->workload as $row)
                        <div class="workload-row" wire:key="wl-{{ $row['user']->id }}">
                            <span class="avatar-stack">
                                <span class="avatar avatar--blue">
                                    {{ getInitials($row['user']->name, '') }}
                                </span>
                                <span class="name">{{ $row['user']->name }}</span>
                            </span>
                            <span class="workload-bar">
                                <span style="width:{{ $row['percentage'] }}%"></span>
                            </span>
                            <span class="workload-count">{{ $row['count'] }}</span>
                        </div>
                    @empty
                        <div style="padding:32px;text-align:center;color:var(--color-text-2);font-size:13px">
                            هیچ سفارش بازی وجود ندارد.
                        </div>
                    @endforelse

                </div>
            </section>

        </div>

        {{-- ==================== Dash Grid Even — Activity + Status ==================== --}}
        <div class="dash-grid dash-grid--even">

            {{-- فعالیت‌های اخیر --}}
            <section class="card">
                <header class="card-head">
                    <h2>فعالیت‌های اخیر</h2>
                    <span class="card-sub">آخرین تغییرات وضعیت</span>
                </header>
                <ul class="timeline">

                    @forelse($this->recentActivity as $h)
                        <li class="tl-item" wire:key="act-{{ $h->id }}">
                            <span class="tl-avatar">
                                <span class="avatar avatar--sm avatar--blue">
                                    {{ getInitials($h->changedBy?->name ?? '?', '') }}
                                </span>
                            </span>
                            <div class="tl-body">
                                <div class="tl-text">
                                    <strong>{{ $h->changedBy?->name ?? '—' }}</strong>
                                    وضعیت را به
                                    <span class="badge {{ $h->toStatus?->badge_class ?? 'badge--info' }}"
                                          style="font-size:10px">
                                        {{ $h->toStatus?->label ?? '—' }}
                                    </span>
                                    تغییر داد روی
                                    <a href="{{ route('work-orders.detail', $h->work_order_id) }}"
                                       wire:navigate.hover
                                       class="mono">{{ $h->workOrder?->code }}</a>
                                </div>
                                @if($h->note)
                                    <div class="tl-meta">{{ $h->note }}</div>
                                @endif
                                <div class="tl-time">
                                    {{ verta($h->created_at)->format('%d %B - H:i') }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li style="padding:32px;text-align:center;color:var(--color-text-2);font-size:13px">
                            فعالیتی ثبت نشده.
                        </li>
                    @endforelse

                </ul>
            </section>

            {{-- دستورکارها بر اساس وضعیت --}}
            <section class="card">
                <header class="card-head">
                    <h2>دستورکارها بر اساس وضعیت</h2>
                    <span class="card-sub">در محدوده دسترسی شما</span>
                </header>
                <div style="padding:8px 0 12px">

                    @forelse($this->statusBreakdown as $s)
                        <div class="status-row" wire:key="sb-{{ $s['key'] }}">
                            <span class="badge {{ $s['badge_class'] }}">
                                <span class="badge__dot"></span>{{ $s['label'] }}
                            </span>
                            <span class="status-bar">
                                <span style="width:{{ $s['percentage'] }}%"></span>
                            </span>
                            <span class="status-count">{{ $s['count'] }}</span>
                        </div>
                    @empty
                        <div style="padding:32px;text-align:center;color:var(--color-text-2);font-size:13px">
                            وضعیتی تعریف نشده.
                        </div>
                    @endforelse

                </div>
            </section>

        </div>

    </div>
</div>
