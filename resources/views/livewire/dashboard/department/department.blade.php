<div id="view" style="padding: 50px 50px">

    <!-- ==================== Page Head ==================== -->
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">واحد ها</h1>
        </div>

        <div class="page-head-actions d-flex align-items-center gap-2">

            <button wire:click="open" class="btn btn--primary" data-action="new-dep">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                واحد جدید
            </button>
        </div>
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

                                            @foreach($this->getUsers() as $key => $value)
                                                <option value="{{ $value }}">{{ $key }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    @error('manager') <span class="field-error">{{ $message }}</span> @enderror
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
                                <a href="{{ route('detail-work-orders') }}"
                                   wire:navigate.hover
                                   class="wo-link">
                                    {{ $department->code }}
                                </a>
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
                            @foreach ($department->users as $user)
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

                            <td class="cell-actions">
                                <button class="icon-btn icon-btn--sm"
                                        aria-label="اقدامات برای DEP-{{ $department->id }}">
                                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <circle cx="5" cy="12" r="1.4"/>
                                        <circle cx="12" cy="12" r="1.4"/>
                                        <circle cx="19" cy="12" r="1.4"/>
                                    </svg>
                                </button>
                            </td>
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

{{--            <!-- ==================== Pagination ==================== -->--}}
{{--            <div class="pagination d-flex justify-content-between align-items-center flex-wrap gap-3">--}}
{{--                <div class="pagination-info">نمایش <b>1–10</b> از <b>34</b> دستورکار</div>--}}
{{--                <div class="pagination-controls d-flex align-items-center gap-1">--}}
{{--                    <button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="0" disabled aria-label="صفحه قبل">--}}
{{--                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"--}}
{{--                             stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>--}}
{{--                    </button>--}}
{{--                    <button class="page-btn is-active" data-action="page" data-page="1">1</button>--}}
{{--                    <button class="page-btn" data-action="page" data-page="2">2</button>--}}
{{--                    <button class="page-btn" data-action="page" data-page="3">3</button>--}}
{{--                    <span class="page-ellipsis">…</span>--}}
{{--                    <button class="page-btn" data-action="page" data-page="4">4</button>--}}
{{--                    <button class="icon-btn icon-btn--bordered icon-btn--sm" data-action="page" data-page="2" aria-label="صفحه بعد">--}}
{{--                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"--}}
{{--                             stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>

</div>
