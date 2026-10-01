<div id="view" style="padding: 50px 50px">

    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">کاربران Output Messenger</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">
            <button wire:click="open" class="btn btn--primary">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.1" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                حساب جدید
            </button>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModal') }"
         x-init="$watch('open', v => document.body.classList.toggle('is-locked', v))">
        @if($showModal)
            <div class="modal-backdrop" wire:click.self="close">
                <div class="modal" role="dialog" aria-modal="true">
                    <div class="modal-head">
                        <div>
                            <h2>{{ $accountId ? 'ویرایش حساب' : 'افزودن حساب Output' }}</h2>
                            <p>{{ $accountId ? 'اطلاعات حساب را ویرایش کنید.' : 'کاربر و اطلاعات Output او را وارد کنید.' }}</p>
                        </div>
                        <button type="button" class="icon-btn" wire:click="close" aria-label="بستن">✕</button>
                    </div>

                    <form wire:submit="save" novalidate>
                        <div class="modal-body">
                            <div class="form-grid">

                                {{-- کاربر --}}
                                <div class="field @error('user_id') has-error @enderror" style="grid-column: span 2">
                                    <label>کاربر داخلی <span class="req">*</span></label>

                                    <div class="user-combobox"
                                         x-data="{ open: false }"
                                         @click.outside="open = false">

                                        @if($user_id && $selectedUserName)
                                            {{-- ✅ حالت انتخاب شده --}}
                                            <div class="user-combobox__selected">
                <span class="avatar avatar--sm avatar--blue">
                    {{ getInitials($selectedUserName, '') }}
                </span>
                                                <span class="user-combobox__selected-name">{{ $selectedUserName }}</span>

                                                <button type="button"
                                                        class="user-combobox__clear"
                                                        wire:click="clearUser"
                                                        title="تغییر کاربر">
                                                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                        <path d="M18 6 6 18M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            {{-- 🔍 حالت جستجو --}}
                                            <div class="global-search" style="width:100%">
                                                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="1.9" stroke-linecap="round">
                                                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
                                                </svg>
                                                <input type="text"
                                                       wire:model.live.debounce.300ms="userSearch"
                                                       @focus="open = true"
                                                       placeholder="جستجو بر اساس نام، نام کاربری، کد پرسنلی...">
                                            </div>

                                            {{-- 📋 dropdown نتایج --}}
                                            <div class="user-combobox__dropdown"
                                                 x-show="open"
                                                 x-cloak
                                                 wire:key="user-dropdown-{{ md5($userSearch) }}">

                                                @forelse($this->searchableUsers as $u)
                                                    <button type="button"
                                                            class="user-combobox__item"
                                                            wire:click="selectUser({{ $u->id }})"
                                                            @click="open = false">

                        <span class="avatar avatar--sm avatar--blue">
                            {{ getInitials($u->name ?? '?', '') }}
                        </span>

                                                        <div class="user-combobox__info">
                                                            <div class="user-combobox__name">
                                                                {{ $u->name ?? '—' }}
                                                            </div>
                                                            <div class="user-combobox__meta" dir="ltr">
                                                                {{ $u->username }}
                                                                @if($u->employee_code)
                                                                    • {{ $u->employee_code }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </button>
                                                @empty
                                                    <div class="user-combobox__empty">
                                                        @if($userSearch !== '')
                                                            کاربری با این مشخصات یافت نشد.
                                                        @else
                                                            کاربری برای افزودن موجود نیست.
                                                        @endif
                                                    </div>
                                                @endforelse

                                                @if($this->searchableUsers->count() >= 10)
                                                    <div class="user-combobox__footer">
                                                        برای دیدن نتایج بیشتر، جستجو را دقیق‌تر کنید
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    @error('user_id') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">اطلاعات Output Messenger</div>

                                {{-- Output User ID --}}
                                <div class="field @error('output_user_id') has-error @enderror">
                                    <label>Output User ID</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="output_user_id"
                                               placeholder="مثلاً: 12345" dir="ltr">
                                    </div>
                                    <small style="color:var(--color-text-2);font-size:11px">
                                        شناسه یکتای کاربر در Output Messenger (توصیه‌شده)
                                    </small>
                                    @error('output_user_id') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- Output Username --}}
                                <div class="field @error('output_username') has-error @enderror">
                                    <label>نام کاربری Output</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="output_username"
                                               placeholder="ali.rezaei" dir="ltr">
                                    </div>
                                    @error('output_username') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div class="field @error('output_email') has-error @enderror">
                                    <label>ایمیل</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="email" wire:model="output_email"
                                               placeholder="ali@example.com" dir="ltr">
                                    </div>
                                    @error('output_email') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- Mobile --}}
                                <div class="field @error('output_mobile') has-error @enderror">
                                    <label>موبایل</label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="output_mobile"
                                               placeholder="0912..." dir="ltr">
                                    </div>
                                    @error('output_mobile') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-section-label">تنظیمات نوتیفیکیشن</div>

                                <div class="field">
                                    <label>فعال بودن حساب</label>
                                    <label class="switch">
                                        <input type="checkbox" wire:model="is_active">
                                        <span>فعال</span>
                                    </label>
                                </div>

                                <div class="field">
                                    <label>نوتیف هنگام تخصیص سفارش</label>
                                    <label class="switch">
                                        <input type="checkbox" wire:model="notify_on_assign">
                                        <span>ارسال شود</span>
                                    </label>
                                </div>

                                <div class="field" style="grid-column: span 2">
                                    <label>نوتیف هنگام تغییر وضعیت</label>
                                    <label class="switch">
                                        <input type="checkbox" wire:model="notify_on_status_change">
                                        <span>ارسال شود</span>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="modal-foot">
                            <span class="spacer"></span>
                            <button type="button" class="btn" wire:click="close">انصراف</button>
                            <button type="submit" class="btn btn--primary"
                                    wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    {{ $accountId ? 'ذخیره تغییرات' : 'افزودن حساب' }}
                                </span>
                                <span wire:loading wire:target="save">در حال ذخیره…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="toolbar d-flex align-items-center flex-wrap gap-2">
        <div class="toolbar-search flex-grow-1">
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.9" stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            </svg>
            <input type="search" wire:model.live.debounce.400ms="search"
                   placeholder="جستجو بر اساس نام، ID، ایمیل، موبایل…">
        </div>

        <select class="select" wire:model.live="status">
            <option value="">همه وضعیت‌ها</option>
            <option value="1">فعال</option>
            <option value="0">غیرفعال</option>
        </select>

        <button wire:click="resetFilters" class="btn btn--ghost btn--sm"
                @if($status === '') disabled @endif>
            پاک کردن
        </button>
    </div>

    <div id="table-region">
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>کاربر داخلی</th>
                        <th>Output ID</th>
                        <th>Output Username</th>
                        <th>ایمیل</th>
                        <th>موبایل</th>
                        <th>نوتیف تخصیص</th>
                        <th>وضعیت</th>
                        <th>آخرین ارسال</th>
                        <th class="cell-actions"><span class="sr-only">اقدامات</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($this->accounts as $a)
                        <tr wire:key="om-{{ $a->id }}">
                            <td>{{ $a->id }}</td>

                            <td>
                                <span class="avatar-stack">
                                    <span class="avatar avatar--sm avatar--blue">
                                        {{ getInitials($a->user?->name ?? '?', '') }}
                                    </span>
                                    <span class="name">{{ $a->user?->name ?? '—' }}</span>
                                </span>
                            </td>

                            <td style="direction:ltr;text-align:right;font-family:monospace">
                                {{ $a->output_user_id ?? '—' }}
                            </td>
                            <td style="direction:ltr;text-align:right">{{ $a->output_username ?? '—' }}</td>
                            <td style="direction:ltr;text-align:right">{{ $a->output_email ?? '—' }}</td>
                            <td style="direction:ltr;text-align:right">{{ $a->output_mobile ?? '—' }}</td>

                            <td>
                                @if($a->notify_on_assign)
                                    <span class="badge badge--success">روشن</span>
                                @else
                                    <span class="badge badge--info">خاموش</span>
                                @endif
                            </td>

                            <td>
                                @if($a->is_active)
                                    <span class="badge badge--success">
                                        <span class="badge__dot"></span>فعال
                                    </span>
                                @else
                                    <span class="badge badge--danger">
                                        <span class="badge__dot"></span>غیرفعال
                                    </span>
                                @endif
                            </td>

                            <td class="cell-date">
                                {{ $a->last_notified_at ? verta($a->last_notified_at)->format('%d %B - H:i') : '—' }}
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

                                <button x-ref="trigger" class="icon-btn icon-btn--sm"
                                        type="button" @click.stop="toggle()">
                                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.4"/>
                                        <circle cx="12" cy="12" r="1.4"/>
                                        <circle cx="19" cy="12" r="1.4"/>
                                    </svg>
                                </button>

                                <template x-teleport="body">
                                    <div class="row-menu"
                                         x-show="open" x-cloak
                                         x-transition.opacity.duration.120ms
                                         :style="`top:${y}px; left:${x}px;`"
                                         @click.outside="open = false"
                                         @keydown.escape.window="open = false">

                                        <button class="row-menu__item" type="button"
                                                @click="open = false"
                                                wire:click="open({{ $a->id }})">
                                            ویرایش
                                        </button>

                                        <button class="row-menu__item" type="button"
                                                @click="open = false"
                                                wire:click="changeStatus({{ $a->id }})">
                                            {{ $a->is_active ? 'غیر فعال کردن' : 'فعال کردن' }}
                                        </button>

                                        <div class="row-menu__divider"></div>

                                        <button class="row-menu__item row-menu__item--danger"
                                                type="button" @click="open = false"
                                                wire:click="delete({{ $a->id }})"
                                                wire:confirm="از حذف این حساب مطمئن هستید؟">
                                            حذف
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-4" style="text-align:center">
                                هیچ حسابی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $this->accounts->onEachSide(1)->links('livewire.custom-pagination') }}
            </div>
        </div>
    </div>

</div>
