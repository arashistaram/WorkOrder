<div id="view" style="padding: 50px 50px">

    {{-- ==================== Page Head ==================== --}}
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">کاربران</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">
            <button wire:click="open" class="btn btn--primary" data-action="new-user">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                کاربر جدید
            </button>
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
                                {{ $userId ? 'ویرایش کاربر' : 'ایجاد کاربر جدید' }}
                            </h2>
                            <p>
                                {{ $userId
                                    ? 'اطلاعات کاربر را به‌روزرسانی کنید.'
                                    : 'اطلاعات کاربر جدید را وارد کنید.' }}
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

                                {{-- نام --}}
                                <div class="field @error('name') has-error @enderror" style="grid-column: span 2">
                                    <label>نام و نام خانوادگی</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="name" placeholder="مثلاً: علی رضایی">
                                    </div>
                                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- نام کاربری --}}
                                <div class="field @error('username') has-error @enderror">
                                    <label>نام کاربری <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="username" placeholder="ali.rezaei" dir="ltr">
                                    </div>
                                    @error('username') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- نقش --}}
                                <div class="field @error('role') has-error @enderror">
                                    <label>نقش <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <select wire:model="role">
                                            <option value="admin">مدیر سیستم</option>
                                            <option value="manager">مدیر</option>
                                            <option value="user">کاربر</option>
                                        </select>
                                    </div>
                                    @error('role') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- رمز عبور --}}
                                <div class="field @error('password') has-error @enderror">
                                    <label>
                                        رمز عبور
                                        @if(!$userId) <span class="req">*</span> @endif
                                    </label>
                                    <div class="global-search" style="width:100%">
                                        <input type="password" wire:model="password"
                                               placeholder="{{ $userId ? 'برای تغییر پر کنید' : 'حداقل ۶ کاراکتر' }}"
                                               dir="ltr" autocomplete="new-password">
                                    </div>
                                    @error('password') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- تکرار رمز --}}
                                <div class="field @error('password_confirmation') has-error @enderror">
                                    <label>تکرار رمز عبور</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="password" wire:model="password_confirmation"
                                               placeholder="تکرار رمز" dir="ltr" autocomplete="new-password">
                                    </div>
                                    @error('password_confirmation') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">اطلاعات شغلی</div>

                                {{-- کد پرسنلی --}}
                                <div class="field @error('employee_code') has-error @enderror">
                                    <label>کد پرسنلی</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="employee_code" placeholder="EMP-001" dir="ltr">
                                    </div>
                                    @error('employee_code') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- عنوان شغلی --}}
                                <div class="field @error('job_title') has-error @enderror">
                                    <label>عنوان شغلی</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="job_title" placeholder="مثلاً: کارشناس فروش">
                                    </div>
                                    @error('job_title') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- تلفن --}}
                                <div class="field @error('phone') has-error @enderror">
                                    <label>تلفن</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="phone" placeholder="0912-1234567" dir="ltr">
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
                                    {{ $userId ? 'ذخیره تغییرات' : 'ایجاد کاربر' }}
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
                   id="list-search"
                   placeholder="جستجو بر اساس نام، نام کاربری، کد پرسنلی…"
                   aria-label="جستجوی کاربران"
                   autocomplete="off">
        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">

            <select class="select" wire:model.live="roleFilter" aria-label="فیلتر نقش">
                <option value="">همه نقش‌ها</option>
                <option value="admin">مدیر سیستم</option>
                <option value="manager">مدیر</option>
                <option value="user">کاربر</option>
            </select>

        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">
            <select class="select" wire:model.live="status" aria-label="فیلتر وضعیت">
                <option value="">همه وضعیت‌ها</option>
                <option value="1">فعال</option>
                <option value="0">غیرفعال</option>
            </select>
        </div>

        <button wire:click="resetStatus"
                class="btn btn--ghost btn--sm"
                id="clear-filters"
                @if($status === '' && $roleFilter === '') disabled @endif>
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
                            <button class="th-sort" wire:click="sortBy('id')">
                                شناسه
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('name')">
                                نام
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('username')">
                                نام کاربری
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>کد پرسنلی</th>
                        <th>عنوان شغلی</th>
                        <th>تلفن</th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('role')">
                                نقش
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
                    @forelse ($this->users as $user)
                        <tr wire:key="user-{{ $user->id }}">

                            <td class="cell-check">
                                <input type="checkbox" class="checkbox"
                                       aria-label="انتخاب USR-{{ $user->id }}">
                            </td>

                            <td class="cell-wo" data-label="شناسه">
                                <span class="wo-link">{{ $user->id }}</span>
                            </td>

                            <td class="cell-title" data-label="نام">
                                <span class="title-text">{{ $user->name ?? '—' }}</span>
                            </td>

                            <td class="cell-customer" data-label="نام کاربری">
                                <div class="cell-sub" style="color:var(--color-text-2); direction:ltr; text-align:right;">
                                    {{ $user->username }}
                                </div>
                            </td>

                            <td class="cell-due" data-label="کد پرسنلی">
                                <span class="badge badge--info" style="direction:ltr;">
                                    {{ $user->employee_code ?? '—' }}
                                </span>
                            </td>

                            <td class="cell-assignee" data-label="عنوان شغلی">
                                {{ $user->job_title ?? '—' }}
                            </td>

                            <td class="cell-priority" data-label="تلفن">
                                <span class="badge badge--info" style="direction:ltr;">
                                    {{ $user->phone ?? '—' }}
                                </span>
                            </td>

                            <td class="cell-status" data-label="نقش">
                                @php
                                    $roleMap = [
                                        'admin'   => ['label' => 'مدیر سیستم', 'class' => 'badge--danger'],
                                        'manager' => ['label' => 'مدیر',       'class' => 'badge--warning'],
                                        'user'    => ['label' => 'کاربر',      'class' => 'badge--info'],
                                    ];
                                    $r = $roleMap[$user->role] ?? ['label' => $user->role, 'class' => 'badge--info'];
                                @endphp
                                <span class="badge {{ $r['class'] }}">{{ $r['label'] }}</span>
                            </td>

                            <td class="cell-status" data-label="وضعیت">
                                @if ($user->is_active)
                                    <span class="badge badge--success">
                                        <span class="badge__dot"></span>فعال
                                    </span>
                                @else
                                    <span class="badge badge--danger">
                                        <span class="badge__dot"></span>غیرفعال
                                    </span>
                                @endif
                            </td>

                            <td class="cell-created cell-date" data-label="ایجاد">
                                {{ verta($user->created_at)->format('%d %B') }}
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
                                        aria-haspopup="menu"
                                        aria-label="اقدامات برای USR-{{ $user->id }}">
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
                                                wire:click="open({{ $user->id }})">
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
                                                wire:click="changeStatus({{ $user->id }})">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M5 12h14"/>
                                            </svg>
                                            {{ $user->is_active ? 'غیر فعال کردن' : 'فعال کردن' }}
                                        </button>

                                        <div class="row-menu__divider"></div>

                                        <button class="row-menu__item row-menu__item--danger"
                                                type="button"
                                                role="menuitem"
                                                @click="open = false"
                                                wire:click="delete({{ $user->id }})"
                                                wire:confirm="از حذف این کاربر مطمئن هستید؟">
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

                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-4" style="text-align: center">
                                هیچ کاربری یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $this->users->onEachSide(1)->links('livewire.custom-pagination') }}

            </div>
        </div>
    </div>

</div>
