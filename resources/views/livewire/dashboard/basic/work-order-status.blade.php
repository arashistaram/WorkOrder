<div id="view" style="padding: 50px 50px">

    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">وضعیت‌های سفارش کار</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">
            <button wire:click="open" class="btn btn--primary" data-action="new-status">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                وضعیت جدید
            </button>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModal') }"
         x-init="$watch('open', v => document.body.classList.toggle('is-locked', v))">
        @if($showModal)
            <div class="modal-backdrop" wire:click.self="close">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">

                    <div class="modal-head">
                        <div>
                            <h2 id="modal-title">
                                {{ $statusId ? 'ویرایش وضعیت' : 'ایجاد وضعیت جدید' }}
                            </h2>
                            <p>
                                {{ $statusId
                                    ? 'اطلاعات وضعیت را به‌روزرسانی کنید.'
                                    : 'اطلاعات وضعیت جدید را وارد کنید.' }}
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

                                {{-- برچسب --}}
                                <div class="field @error('label') has-error @enderror" style="grid-column: span 2">
                                    <label>برچسب وضعیت <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="label"
                                               placeholder="مثلاً: در انتظار تخصیص">
                                    </div>
                                    @error('label') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- کلید --}}
                                <div class="field @error('key') has-error @enderror">
                                    <label>کلید (انگلیسی) <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <input type="text" wire:model="key"
                                               placeholder="pending"
                                               dir="ltr" style="text-transform:lowercase">
                                    </div>
                                    <small style="color:var(--color-text-2);font-size:11px">
                                        فقط حروف انگلیسی، اعداد، <code>-</code> و <code>_</code>
                                    </small>
                                    @error('key') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- رنگ --}}
                                <div class="field @error('color') has-error @enderror">
                                    <label>رنگ <span class="req">*</span></label>
                                    <div class="global-search" style="width:100%">
                                        <select wire:model="color">
                                            @foreach($this->colorOptions as $value => $opt)
                                                <option value="{{ $value }}">{{ $opt['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('color') <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- پیش‌نمایش --}}
                                <div class="field" style="grid-column: span 2">
                                    <label>پیش‌نمایش</label>
                                    <div style="padding:8px 0">
                                        @php
                                            $c = $this->colorOptions[$color]['hex'] ?? '#6b7280';
                                            $badgeClass = match($color) {
                                                'green'  => 'badge--success',
                                                'red'    => 'badge--danger',
                                                'yellow' => 'badge--warning',
                                                'blue', 'purple' => 'badge--info',
                                                default  => 'badge--info',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            <span class="badge__dot"></span>
                                            {{ $label ?: 'برچسب وضعیت' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-section-label">تنظیمات</div>

                                {{-- final --}}
                                <div class="field">
                                    <label>وضعیت نهایی</label>
                                    <label class="switch">
                                        <input type="checkbox" wire:model="is_final">
                                        <span>وضعیت پایانی است</span>
                                    </label>
                                    <small style="color:var(--color-text-2);font-size:11px">
                                        وضعیت‌های نهایی مثل «تکمیل شده» یا «لغو شده»
                                    </small>
                                </div>

                                {{-- active --}}
                                <div class="field">
                                    <label>فعال بودن</label>
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
                                    {{ $statusId ? 'ذخیره تغییرات' : 'ایجاد وضعیت' }}
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
                   placeholder="جستجو بر اساس کلید یا برچسب…"
                   aria-label="جستجوی وضعیت‌ها"
                   autocomplete="off">
        </div>

        <div class="toolbar-filters d-flex align-items-center flex-wrap gap-2">
            <select class="select" wire:model.live="finalFilter" aria-label="فیلتر نهایی">
                <option value="">همه</option>
                <option value="1">نهایی</option>
                <option value="0">غیر نهایی</option>
            </select>

            <select class="select" wire:model.live="status" aria-label="فیلتر وضعیت">
                <option value="">همه وضعیت‌ها</option>
                <option value="1">فعال</option>
                <option value="0">غیرفعال</option>
            </select>
        </div>

        <button wire:click="resetStatus"
                class="btn btn--ghost btn--sm"
                id="clear-filters"
                @if($status === '' && $finalFilter === '') disabled @endif>
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
                            <button class="th-sort" wire:click="sortBy('key')">
                                کلید
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('label')">
                                برچسب
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('color')">
                                رنگ
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </th>
                        <th>
                            <button class="th-sort" wire:click="sortBy('is_final')">
                                نهایی
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
                    @forelse ($this->statuses as $status)
                        <tr wire:key="status-{{ $status->id }}">

                            <td class="cell-check">
                                <input type="checkbox" class="checkbox"
                                       aria-label="انتخاب STS-{{ $status->id }}">
                            </td>

                            <td class="cell-wo" data-label="شناسه">
                                <span class="wo-link">{{ $status->id }}</span>
                            </td>

                            <td class="cell-customer" data-label="کلید">
                                <div class="cell-sub" style="direction:ltr;text-align:right;font-family:monospace">
                                    {{ $status->key }}
                                </div>
                            </td>

                            <td class="cell-title" data-label="برچسب">
                                <span class="title-text">{{ $status->label }}</span>
                            </td>

                            <td class="cell-status" data-label="رنگ">
                                <span class="badge {{ $status->badge_class }}">
                                    <span class="badge__dot"></span>
                                    {{ $status->label }}
                                </span>
                            </td>

                            <td class="cell-status" data-label="نهایی">
                                @if($status->is_final)
                                    <span class="badge badge--success">
                                        <span class="badge__dot"></span>نهایی
                                    </span>
                                @else
                                    <span class="badge badge--info">غیر نهایی</span>
                                @endif
                            </td>

                            <td class="cell-status" data-label="وضعیت">
                                @if ($status->is_active)
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
                                {{ verta($status->created_at)->format('%d %B') }}
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
                                        aria-label="اقدامات برای {{ $status->key }}">
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
                                                wire:click="open({{ $status->id }})">
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
                                                wire:click="toggleFinal({{ $status->id }})">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                            {{ $status->is_final ? 'حذف علامت نهایی' : 'علامت‌گذاری نهایی' }}
                                        </button>

                                        <button class="row-menu__item"
                                                type="button"
                                                role="menuitem"
                                                @click="open = false"
                                                wire:click="changeStatus({{ $status->id }})">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M5 12h14"/>
                                            </svg>
                                            {{ $status->is_active ? 'غیر فعال کردن' : 'فعال کردن' }}
                                        </button>

                                        <div class="row-menu__divider"></div>

                                        <button class="row-menu__item row-menu__item--danger"
                                                type="button"
                                                role="menuitem"
                                                @click="open = false"
                                                wire:click="delete({{ $status->id }})"
                                                wire:confirm="از حذف این وضعیت مطمئن هستید؟">
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
                            <td colspan="9" class="p-4" style="text-align: center">
                                هیچ وضعیتی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $this->statuses->onEachSide(1)->links('livewire.custom-pagination') }}

            </div>
        </div>
    </div>

</div>
