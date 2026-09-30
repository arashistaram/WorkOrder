<div id="view" style="padding: 50px 50px">

    {{-- ==================== Page Head ==================== --}}
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">سفارش‌های کار</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">
            @can('create', \App\Models\WorkOrder::class)
                <button wire:click="open" class="btn btn--primary" data-action="new-wo">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                         stroke-linecap="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    سفارش کار جدید
                </button>
            @endcan
        </div>
    </div>

    {{-- ==================== Modal ==================== --}}
    <div x-data="{ open: @entangle('showModal') }"
         x-init="$watch('open', v => document.body.classList.toggle('is-locked', v))">
        @if($showModal)
            <div class="modal-backdrop" wire:click.self="close">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">

                    <div class="modal-head">
                        <div>
                            <h2 id="modal-title">
                                {{ $workOrderId ? 'ویرایش سفارش کار' : 'ایجاد سفارش کار جدید' }}
                            </h2>
                            <p>
                                {{ $workOrderId
                                    ? 'اطلاعات سفارش کار را به‌روزرسانی کنید.'
                                    : 'اطلاعات سفارش کار جدید را وارد کنید.' }}
                            </p>
                        </div>
                        <button type="button" class="icon-btn" wire:click="close" aria-label="بستن">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" novalidate>
                        <div class="modal-body">
                            <div class="form-grid">

                                {{-- عنوان --}}
                                <div class="field @error('title') has-error @enderror" style="grid-column: span 2">
                                    <label>عنوان <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="title"
                                               placeholder="مثلاً: تعمیر پرینتر طبقه دوم">
                                    </div>
                                    @error('title') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- توضیحات --}}
                                <div class="field @error('description') has-error @enderror" style="grid-column: span 2">
                                    <label>توضیحات</label>
                                    <div class="global-search" style="width:100%">
                                        <textarea wire:model="description" rows="3"
                                                  placeholder="شرح کامل درخواست..."></textarea>
                                    </div>
                                    @error('description') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">تخصیص و وضعیت</div>

                                {{-- دپارتمان --}}
                                <div class="field @error('department_id') has-error @enderror">
                                    <label>واحد <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <select wire:model="department_id">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($this->departmentsList as $d)
                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('department_id') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- وضعیت --}}
                                <div class="field @error('status_id') has-error @enderror">
                                    <label>وضعیت <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <select wire:model="status_id">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($this->statusesList as $s)
                                                <option value="{{ $s->id }}">{{ $s->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if(! $workOrderId)
                                        <small style="color:var(--color-text-2);font-size:11px">
                                            در این مرحله فقط می‌توانید «پیش‌نویس» یا «در انتظار تخصیص» را انتخاب کنید.
                                            سایر وضعیت‌ها پس از تخصیص توسط مسئول واحد قابل انتخاب هستند.
                                        </small>
                                    @endif

                                    @error('status_id') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- اولویت --}}
                                <div class="field @error('priority_id') has-error @enderror">
                                    <label>اولویت <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <select wire:model="priority_id">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($this->prioritiesList as $p)
                                                <option value="{{ $p->id }}">{{ $p->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('priority_id') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">زمان‌بندی</div>

                                {{-- تاریخ سررسید --}}
                                <div class="field @error('due_year') has-error @enderror @error('due_month') has-error @enderror @error('due_day') has-error @enderror"
                                     style="grid-column: span 2">
                                    <label>تاریخ سررسید</label>

                                    <div class="date-triple">

                                        {{-- سال --}}
                                        <div class="date-triple__item">
                                            <select wire:model.live="due_year" aria-label="سال">
                                                <option value="">سال</option>
                                                @foreach($this->persianYears as $y)
                                                    <option value="{{ $y }}">{{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- ماه --}}
                                        <div class="date-triple__item">
                                            <select wire:model.live="due_month" aria-label="ماه">
                                                <option value="">ماه</option>
                                                @foreach($this->persianMonths as $num => $name)
                                                    <option value="{{ $num }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- روز --}}
                                        <div class="date-triple__item">
                                            <select wire:model.live="due_day" aria-label="روز">
                                                <option value="">روز</option>
                                                @foreach(range(1, 31) as $d)
                                                    <option value="{{ $d }}">{{ $d }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- دکمه پاک‌کردن --}}
                                        @if($due_year || $due_month || $due_day)
                                            <button type="button"
                                                    class="date-triple__clear"
                                                    wire:click="$set('due_year', null); $set('due_month', null); $set('due_day', null)"
                                                    title="پاک کردن تاریخ">
                                                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                    <path d="M18 6 6 18M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- پیش‌نمایش --}}
                                    @if($due_year && $due_month && $due_day)
                                        <small style="color:var(--color-text-2);font-size:11px;margin-top:4px;display:block">
                                            📅 {{ $this->persianMonths[$due_month] ?? '' }} {{ $due_day }}، {{ $due_year }}
                                        </small>
                                    @endif

                                    @error('due_year')  <span class="field-error">{{ $message }}</span> @enderror
                                    @error('due_month') <span class="field-error">{{ $message }}</span> @enderror
                                    @error('due_day')   <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- ساعت تخمینی --}}
{{--                                <div class="field @error('estimated_hours') has-error @enderror">--}}
{{--                                    <label>ساعت تخمینی</label>--}}
{{--                                    <div class="global-search" style="width:100%">--}}
{{--                                        <input type="number" step="0.25" min="0" wire:model="estimated_hours"--}}
{{--                                               placeholder="مثلاً: 2.5" dir="ltr">--}}
{{--                                    </div>--}}
{{--                                    @error('estimated_hours') <span class="field-error">{{ $message }}</span> @enderror--}}
{{--                                </div>--}}

                            </div>
                        </div>

                        <div class="modal-foot">
                            <span class="spacer"></span>
                            <button type="button" class="btn" wire:click="close">انصراف</button>
                            <button type="submit"
                                    class="btn btn--primary"
                                    wire:loading.attr="disabled"
                                    wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    {{ $workOrderId ? 'ذخیره تغییرات' : 'ایجاد سفارش کار' }}
                                </span>
                                <span wire:loading wire:target="save">در حال ذخیره…</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif
    </div>

    {{-- ==================== Toolbar ==================== --}}
    <div class="toolbar d-flex align-items-center flex-wrap gap-2">

        <div class="toolbar-search flex-grow-1">
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                 stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            </svg>
            <input type="search"
                   wire:model.live.debounce.400ms="search"
                   placeholder="جستجو بر اساس کد، عنوان، توضیحات…"
                   aria-label="جستجوی سفارش‌ها"
                   autocomplete="off">
        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">
            <select class="select" wire:model.live="statusFilter" aria-label="وضعیت">
                <option value="">همه وضعیت‌ها</option>
                @foreach($this->allStatusesList as $s)
                    <option value="{{ $s->id }}">{{ $s->label }}</option>
                @endforeach
            </select>

            <select class="select" wire:model.live="priorityFilter" aria-label="اولویت">
                <option value="">همه اولویت‌ها</option>
                @foreach($this->prioritiesList as $p)
                    <option value="{{ $p->id }}">{{ $p->label }}</option>
                @endforeach
            </select>

            <select class="select" wire:model.live="deptFilter" aria-label="واحد">
                <option value="">همه واحدها</option>
                @foreach($this->departmentsList as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>

            <select class="select" wire:model.live="assigneeFilter" aria-label="مسئول">
                <option value="">همه مسئول‌ها</option>
                @foreach($this->usersList as $u)
                    <option value="{{ $u->id }}">{{ $u->name ?? $u->username }}</option>
                @endforeach
            </select>

            <label class="switch" title="فقط سفارش‌های عقب‌افتاده">
                <input type="checkbox" wire:model.live="overdueFilter" value="1">
                <span>عقب‌افتاده</span>
            </label>
        </div>

        <button wire:click="resetFilters"
                class="btn btn--ghost btn--sm"
                @if($statusFilter === '' && $priorityFilter === '' && $deptFilter === '' && $assigneeFilter === '' && $overdueFilter === '') disabled @endif>
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
            پاک کردن
        </button>
    </div>

    {{-- ==================== Table ==================== --}}
    <div id="table-region">
        <div class="table-wrap">
            <div class="table-scroll">

                <table class="table">
                    <thead>
                    <tr>
                        <th class="cell-check">
                            <input type="checkbox" class="checkbox" id="select-all" aria-label="انتخاب همه">
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('code')">
                                کد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('title')">
                                عنوان
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>واحد</th>
                        <th>مسئول</th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('priority_id')">
                                اولویت
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('status_id')">
                                وضعیت
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('due_date')">
                                سررسید
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>پیشرفت</th>
                        <th class="cell-actions"><span class="sr-only">اقدامات</span></th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($this->workOrders as $wo)
                        <tr wire:key="wo-{{ $wo->id }}">

                            <td class="cell-check">
                                <input type="checkbox" class="checkbox"
                                       aria-label="انتخاب {{ $wo->code }}">
                            </td>

                            <td class="cell-wo" data-label="کد">
                                <a href="{{ route('work-orders.detail', $wo->id) }}"
                                   wire:navigate.hover
                                   class="wo-link" style="direction:ltr">
                                    {{ $wo->code }}
                                </a>
                            </td>

                            <td class="cell-title" data-label="عنوان">
                                <span class="title-text">{{ $wo->title }}</span>
                                @if($wo->is_overdue)
                                    <span class="badge badge--danger" style="margin-inline-start:6px;font-size:10px">
                                        عقب‌افتاده
                                    </span>
                                @endif
                            </td>

                            <td data-label="واحد">
                                {{ $wo->department?->name ?? '—' }}
                            </td>

                            <td class="cell-assignee" data-label="مسئول">
                                @if($wo->assignee)
                                    <span class="avatar-stack">
                                        <span class="avatar avatar--sm avatar--blue">
                                            {{ getInitials($wo->assignee->name, '') }}
                                        </span>
                                        <span class="name">{{ $wo->assignee->name }}</span>
                                    </span>
                                @else
                                    <span style="color:var(--color-text-2)">—</span>
                                @endif
                            </td>

                            <td data-label="اولویت">
                                @if($wo->priority)
                                    <span class="badge {{ $wo->priority->badge_class }}">
                                        <span class="badge__dot"></span>
                                        {{ $wo->priority->label }}
                                    </span>
                                @endif
                            </td>

                            <td data-label="وضعیت">
                                @if($wo->status)
                                    <span class="badge {{ $wo->status->badge_class }}">
                                        <span class="badge__dot"></span>
                                        {{ $wo->status->label }}
                                    </span>
                                @endif
                            </td>

                            <td class="cell-date" data-label="سررسید">
                                {{ $wo->due_date ? verta($wo->due_date)->format('%d %B') : '—' }}
                            </td>

                            <td data-label="پیشرفت" style="min-width:100px">
                                @php $p = $wo->progress; @endphp
                                <div class="progress-mini" title="{{ $p }}%">
                                    <div class="progress-mini__bar" style="width:{{ $p }}%"></div>
                                </div>
                                <small style="color:var(--color-text-2);font-size:11px">{{ $p }}%</small>
                            </td>

                            <td class="cell-actions"
                                x-data="{
                                    open: false, x: 0, y: 0,
                                    toggle() {
                                        const r = $refs.trigger.getBoundingClientRect();
                                        const menuW = 180, menuH = 200;
                                        let left = r.right - menuW;
                                        if (left < 8) left = 8;
                                        let top = r.bottom + 6;
                                        if (top + menuH > window.innerHeight) {
                                            top = r.top - menuH - 6;
                                            if (top < 8) top = 8;
                                        }
                                        this.x = left; this.y = top;
                                        this.open = !this.open;
                                    }
                                }">

                                <button x-ref="trigger"
                                        class="icon-btn icon-btn--sm"
                                        type="button"
                                        @click.stop="toggle()"
                                        :aria-expanded="open"
                                        aria-haspopup="menu">
                                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <circle cx="5" cy="12" r="1.4"/>
                                        <circle cx="12" cy="12" r="1.4"/>
                                        <circle cx="19" cy="12" r="1.4"/>
                                    </svg>
                                </button>

                                <template x-teleport="body">
                                    <div class="row-menu"
                                         x-show="open"
                                         x-cloak
                                         x-transition.opacity.duration.120ms
                                         :style="`top:${y}px; left:${x}px;`"
                                         role="menu"
                                         @click.outside="open = false"
                                         @keydown.escape.window="open = false">

                                        <a href="{{ route('work-orders.detail', $wo->id) }}"
                                           wire:navigate.hover
                                           class="row-menu__item"
                                           role="menuitem"
                                           @click="open = false">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            جزئیات
                                        </a>

                                        <button class="row-menu__item"
                                                type="button"
                                                role="menuitem"
                                                @click="open = false"
                                                wire:click="open({{ $wo->id }})">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
                                            </svg>
                                            ویرایش
                                        </button>

                                        <div class="row-menu__divider"></div>

                                        <button class="row-menu__item row-menu__item--danger"
                                                type="button"
                                                role="menuitem"
                                                @click="open = false"
                                                wire:click="delete({{ $wo->id }})"
                                                wire:confirm="از حذف این سفارش کار مطمئن هستید؟">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="M6 6l1 14h10l1-14"/>
                                            </svg>
                                            حذف
                                        </button>
                                    </div>
                                </template>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-4" style="text-align: center">
                                هیچ سفارش کاری یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $this->workOrders->onEachSide(1)->links('livewire.custom-pagination') }}

            </div>
        </div>
    </div>

</div>
