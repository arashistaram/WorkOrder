<div id="view" style="padding: 50px 50px">

    <!-- ==================== Page Head ==================== -->
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">واحد ها</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">

            <button style="border: 1px solid #ccc;" wire:click="openAssign" class="btn btn--ghost" data-action="assign-users">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                تخصیص کاربران به واحد
            </button>

            <button wire:click="open" class="btn btn--primary" data-action="new-dep">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                واحد جدید
            </button>

        </div>
    </div>


    {{-- ==================== Modal: تخصیص کاربران ==================== --}}
    <div x-data="{ open: @entangle('showAssignModal') }"
         x-init="$watch('open', v => document.body.classList.toggle('is-locked', v))">
        @if($showAssignModal)
            <div class="modal-backdrop" wire:click.self="closeAssign">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="assign-title">

                    <div class="modal-head">
                        <div>
                            <h2 id="assign-title">تخصیص کاربران به واحد</h2>
                            <p>یک واحد انتخاب کنید، سپس کاربران مورد نظر را به آن اضافه یا حذف کنید.</p>
                        </div>
                        <button type="button" class="icon-btn" wire:click="closeAssign" aria-label="بستن">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveAssign" novalidate>
                        <div class="modal-body">

                            {{-- انتخاب واحد --}}
                            <div class="field @error('assignDepartmentId') has-error @enderror">
                                <label>واحد <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model.live="assignDepartmentId">
                                        <option value="">— یک واحد انتخاب کنید —</option>
                                        @foreach($this->departmentsList as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assignDepartmentId')
                                <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($assignDepartmentId)
                                {{-- جستجو --}}
                                <div class="field" style="margin-top:12px">
                                    <label>جستجوی کاربر</label>
                                    <div class="global-search" style="width:100%">
                                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                                             aria-hidden="true">
                                            <circle cx="11" cy="11" r="7"/>
                                            <path d="m20 20-3.6-3.6"/>
                                        </svg>
                                        <input type="search"
                                               wire:model.live.debounce.300ms="assignSearch"
                                               placeholder="نام یا نام کاربری...">
                                    </div>
                                </div>

                                <div class="form-section-label" style="margin-top:8px">
                                    کاربران
                                    <span class="badge badge--info" style="margin-inline-start:8px">
                                    {{ collect($assignRows)->where('selected', true)->count() }} انتخاب‌شده
                                </span>
                                </div>

                                <div class="assign-list">
                                    @forelse($assignRows as $userId => $row)

                                        <div class="assign-row"
                                             wire:key="assign-{{ $userId }}"
                                             x-data
                                             :class="{ 'is-selected': {{ $row['selected'] ? 'true' : 'false' }} }">

                                            <label class="assign-row__check">
                                                <input type="checkbox"
                                                       class="checkbox"
                                                       wire:model.live="assignRows.{{ $userId }}.selected">
                                                <span class="assign-row__user">
                                                <span class="avatar avatar--sm avatar--blue">
                                                    {{ getInitials($row['name'], '') }}
                                                </span>
                                                <span class="assign-row__info">
                                                    <span class="assign-row__name">{{ $row['name'] }}</span>
                                                    <span class="assign-row__meta" dir="ltr">{{ $row['username'] }}</span>
                                                </span>
                                            </span>
                                            </label>

                                            <select class="select select--sm"
                                                    wire:model="assignRows.{{ $userId }}.role"
                                                @disabled(!$row['selected'])>
                                                <option value="user" @selected(($row['role'] ?? 'user') === 'user')>عضو</option>
                                                <option value="manager" @selected(($row['role'] ?? 'manager') === 'manager')>مدیر</option>
                                            </select>
                                        </div>
                                    @empty
                                        <div class="assigned-empty">کاربری برای نمایش وجود ندارد.</div>
                                    @endforelse
                                </div>
                            @endif

                        </div>

                        <div class="modal-foot">
                            <span class="spacer"></span>
                            <button type="button" class="btn" wire:click="closeAssign">انصراف</button>
                            <button type="submit"
                                    class="btn btn--primary"
                                    wire:loading.attr="disabled"
                                    wire:target="saveAssign">
                                <span wire:loading.remove wire:target="saveAssign">ذخیره تخصیص</span>
                                <span wire:loading wire:target="saveAssign">در حال ذخیره…</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif
    </div>


    <div
        x-data="{ open: @entangle('showModal') }"
        x-init="$watch('open', v => document.body.classList.toggle('is-locked', v))"
    >
        @if($showModal)
            <div class="modal-backdrop" wire:click.self="close">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">

                    <div class="modal-head">
                        <div>
                            <h2 id="modal-title">
                                {{ $departmentId ? 'ویرایش واحد' : 'ایجاد واحد جدید' }}
                            </h2>
                            <p>
                                {{ $departmentId
                                    ? 'اطلاعات واحد را به‌روزرسانی کنید.'
                                    : 'اطلاعات واحد جدید را وارد کنید.' }}
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

                                {{-- نام واحد --}}
                                <div class="field @error('name') has-error @enderror" style="grid-column: span 2">
                                    <label>نام واحد <span class="req">*</span></label>
                                    <div class="global-search" style="width: 100%">
                                        <input type="text"
                                               wire:model="name"
                                               placeholder="نام واحد را وارد کنید ...">
                                    </div>
                                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- کد واحد --}}
                                <div class="field @error('code') has-error @enderror">
                                    <label>کد واحد</label>
                                    <div class="global-search" style="width: 100%">
                                        <input type="text"
                                               wire:model="code"
                                               placeholder="DEP-001"
                                               dir="ltr">
                                    </div>
                                    @error('code') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- مدیر واحد --}}
                                <div class="field @error('manager') has-error @enderror">
                                    <label>انتخاب مدیر واحد</label>
                                    <div class="global-search" style="width: 100%">
                                        <select wire:model="managerId">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($this->getUsers() as $key => $value)
                                                <option value="{{ $value }}">{{ $key }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    @error('managerId')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @endError
                                </div>

                                {{-- توضیحات --}}
                                <div class="field @error('description') has-error @enderror" style="grid-column: span 2">
                                    <label>توضیحات</label>
                                    <div class="global-search" style="width: 100%">
                                        <textarea wire:model="description"
                                              rows="3"
                                              placeholder="شرح کوتاه درباره‌ی وظایف این واحد…"></textarea>
                                    </div>
                                    @error('description') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">تماس و مکان</div>

                                {{-- مکان --}}
                                <div class="field @error('location') has-error @enderror">
                                    <label>مکان</label>
                                    <div class="global-search" style="width: 100%">
                                        <input type="text"
                                               wire:model="location"
                                               placeholder="ساختمان A — طبقه ۲">
                                    </div>
                                    @error('location') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- تلفن --}}
                                <div class="field @error('phone') has-error @enderror">
                                    <label>تلفن</label>
                                    <div class="global-search" style="width: 100%">
                                        <input type="text"
                                               wire:model="phone"
                                               placeholder="021-12345678"
                                               dir="ltr">
                                    </div>
                                    @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- وضعیت --}}
                                <div class="field">
                                    <label>وضعیت</label>
                                    <label class="switch">
                                        <input type="checkbox" wire:model="is_active">
                                        <span>فعال</span>
                                    </label>
                                </div>

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
                                    {{ $departmentId ? 'ذخیره تغییرات' : 'ایجاد واحد' }}
                                </span>
                                <span wire:loading wire:target="save">در حال ذخیره…</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif
    </div>

    <!-- ==================== Toolbar ==================== -->
    <div class="toolbar d-flex align-items-center flex-wrap gap-2">

        <div class="toolbar-search flex-grow-1">
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                 stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            </svg>
            <input type="search" wire:model.live.debounce.400ms="search" id="list-search" placeholder="جستجو بر اساس شماره، عنوان، مشتری…"
                   aria-label="جستجوی واحد ها" autocomplete="off">
        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">
            <select class="select"
                    wire:model.live="status"
                    aria-label="فیلتر بر اساس وضعیت">
                <option value="">همه وضعیت‌ها</option>
                <option value="1">فعال</option>
                <option value="0">غیرفعال</option>
            </select>
        </div>

        <button wire:click="resetStatus" class="btn btn--ghost btn--sm" data-action="clear-filters" id="clear-filters" @if($this->status == '') disabled @endif>
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

                @php
                    /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
                @endphp

                <table class="table">
                    <thead>
                    <tr>
                        <th class="cell-check">
                            <input type="checkbox" class="checkbox" id="select-all"
                                   aria-label="انتخاب همه">
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('id')">
                                شناسه
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('name')">
                                عنوان واحد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('code')">
                                کد واحد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('users')">
                                سرپرست واحد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('phone')">
                                تلفن واحد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('is_active')">
                                وضعیت
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('location')">
                                لوکیشن
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('created_at')">
                                ایجاد
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th class="cell-actions"><span class="sr-only">اقدامات</span></th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($this->departments as $department)
                        <tr wire:key="dept-{{ $department->id }}">

                            <td class="cell-check">
                                <input type="checkbox"
                                       class="checkbox"
                                       aria-label="انتخاب DEP-{{ $department->id }}">
                            </td>

                            <td class="cell-wo" data-label="شناسه">
                                {{ $department->id }}
                            </td>

                            <td class="cell-title" data-label="عنوان واحد">
                                <span class="title-text">{{ $department->name }}</span>
                            </td>

                            <td class="cell-customer" data-label="کد واحد">
                                <div class="cell-sub" style="color:var(--color-text-2)">
                                    {{ $department->code ?? '—' }}
                                </div>
                            </td>

                            <td class="cell-assignee" data-label="سرپرست واحد">
                                <span class="avatar-stack">
                                    @foreach ($department->managers as $user)
                                        <span class="avatar avatar--sm avatar--blue">{{ getInitials($user->name, '') }}</span>
                                        <span class="name">{{ $user->name }}</span>
                                    @endforeach
                                </span>
                            </td>

                            <td class="cell-priority" data-label="تلفن واحد">
                        <span class="badge badge--info">
                            {{ $department->phone ?? '—' }}
                        </span>
                            </td>

                            <td class="cell-status" data-label="وضعیت">
                                @if ($department->is_active)
                                    <span class="badge badge--success">
                                <span class="badge__dot"></span>فعال
                            </span>
                                @else
                                    <span class="badge badge--danger">
                                <span class="badge__dot"></span>غیرفعال
                            </span>
                                @endif
                            </td>

                            <td class="cell-due cell-date" data-label="لوکیشن">
                                {{ $department->location ?? '—' }}
                            </td>

                            <td class="cell-created cell-date" data-label="ایجاد">
                                {{ verta($department->created_at)->format('%d %B') }}
                            </td>

                            @if(auth()->user()?->role === 'admin')

                                <td class="cell-actions"
                                    x-data="{
                                        open: false,
                                        x: 0,
                                        y: 0,
                                        toggle() {
                                            const btn = $refs.trigger;
                                            const r = btn.getBoundingClientRect();
                                            const menuW = 180;
                                            const menuH = 200;

                                            let left = r.right - menuW;
                                            if (left < 8) left = 8;

                                            let top = r.bottom + 6;
                                            if (top + menuH > window.innerHeight) {
                                                top = r.top - menuH - 6;
                                                if (top < 8) top = 8;
                                            }

                                            this.x = left;
                                            this.y = top;
                                            this.open = !this.open;
                                        }
                                    }">

                                    <button x-ref="trigger"
                                            class="icon-btn icon-btn--sm"
                                            type="button"
                                            @click.stop="toggle()"
                                            :aria-expanded="open"
                                            aria-haspopup="menu"
                                            aria-label="اقدامات برای DEP-{{ $department->id }}">
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

                                            <button class="row-menu__item"
                                                    type="button"
                                                    role="menuitem"
                                                    @click="open = false"
                                                    wire:click="open({{ $department->id }})">
                                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
                                                </svg>
                                                ویرایش
                                            </button>

                                            <button class="row-menu__item"
                                                    type="button"
                                                    role="menuitem"
                                                    @click="open = false"
                                                    wire:click="changeStatus({{ $department->id }})">
                                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path d="M5 12h14"/>
                                                </svg>
                                                {{ $department->is_active ? 'غیر فعال کردن' : 'فعال کردن' }}
                                            </button>

                                            <div class="row-menu__divider"></div>

                                            <button class="row-menu__item row-menu__item--danger"
                                                    type="button"
                                                    role="menuitem"
                                                    @click="open = false"
                                                    wire:click="delete({{ $department->id }})"
                                                    wire:confirm="از حذف این واحد مطمئن هستید؟">
                                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="M6 6l1 14h10l1-14"/>
                                                </svg>
                                                حذف
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-4" style="text-align: center">
                                هیچ دپارتمانی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $this->departments->onEachSide(1)->links('livewire.custom-pagination') }}

            </div>


        </div>
    </div>

</div>

@section('script')
    <script>
        (function () {
            const menu = document.getElementById('rowMenu');
            if (!menu) return;

            let currentBtn = null;

            function openMenu(btn) {
                currentBtn = btn;
                btn.setAttribute('aria-expanded', 'true');

                menu.hidden = false;

                const rect = btn.getBoundingClientRect();
                const menuRect = menu.getBoundingClientRect();

                let top  = rect.bottom + window.scrollY + 6;
                let left = rect.right  + window.scrollX - menuRect.width;

                if (left < window.scrollX + 8) {
                    left = rect.left + window.scrollX;
                }

                if (rect.bottom + menuRect.height + 8 > window.innerHeight) {
                    top = rect.top + window.scrollY - menuRect.height - 6;
                }

                menu.style.top  = top + 'px';
                menu.style.left = left + 'px';
            }

            function closeMenu() {
                if (currentBtn) currentBtn.setAttribute('aria-expanded', 'false');
                currentBtn = null;
                menu.hidden = true;
            }

            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('[data-action="row-menu"]');

                if (trigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (currentBtn === trigger) { closeMenu(); return; }
                    closeMenu();
                    openMenu(trigger);
                    return;
                }

                if (menu.contains(e.target)) {
                    const item = e.target.closest('[data-menu-action]');
                    if (item) {
                        const action = item.dataset.menuAction;
                        const id = currentBtn ? currentBtn.dataset.id : null;
                        handleMenuAction(action, id);
                        closeMenu();
                    }
                    return;
                }

                if (!menu.hidden) closeMenu();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !menu.hidden) {
                    const btn = currentBtn;
                    closeMenu();
                    btn && btn.focus();
                }
            });

            window.addEventListener('resize', closeMenu);
            window.addEventListener('scroll', closeMenu, true);

            function handleMenuAction(action, id) {
                switch (action) {
                    case 'view':
                        console.log('view', id);
                        break;
                    case 'edit':
                        console.log('edit', id);
                        break;
                    case 'toggle':
                        console.log('toggle', id);
                        break;
                    case 'delete':
                        if (confirm('از حذف ' + id + ' مطمئن هستید؟')) {
                            console.log('delete', id);
                        }
                        break;
                }
            }
        })();

    </script>
@endsection
