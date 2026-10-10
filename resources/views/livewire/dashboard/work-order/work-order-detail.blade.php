@php
    $wo  = $this->workOrder;
    $can = $this->permissions;
@endphp

<div id="view" style="padding: 50px 50px">

    {{-- ==================== Page Head ==================== --}}
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <h1 class="page-title" style="margin:0">{{ $wo->title }}</h1>

                @if($wo->status)
                    <span class="badge {{ $wo->status->badge_class }}">
                        <span class="badge__dot"></span>{{ $wo->status->label }}
                    </span>
                @endif

                @if($wo->priority)
                    <span class="badge {{ $wo->priority->badge_class }}">
                        <span class="badge__dot"></span>{{ $wo->priority->label }}
                    </span>
                @endif

                @if($wo->is_overdue)
                    <span class="badge badge--danger">عقب‌افتاده</span>
                @endif
            </div>

            <div style="color:var(--color-text-2);margin-top:6px;font-size:13px">
                کد: <span style="direction:ltr;font-family:monospace">{{ $wo->code }}</span>
                &nbsp;•&nbsp;
                واحد: {{ $wo->department?->name ?? '—' }}
                &nbsp;•&nbsp;
                ایجاد: {{ verta($wo->created_at)->format('%d %B %Y') }}
            </div>
        </div>

        {{-- ⬇️ دکمه‌های Action --}}
        <div class="page-head-actions d-flex align-items-center gap-2">
            <a href="{{ route('work-orders') }}" wire:navigate.hover class="btn btn--ghost">
                بازگشت
            </a>

            {{-- ✅ دکمه تخصیص --}}
            @if($can['assign'])
                <button wire:click="openAssignModal" class="btn btn--primary">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                    {{ $wo->assignee_id ? 'تغییر مسئول' : 'تخصیص کاربر' }}
                </button>
            @else
                <button class="btn btn--primary" disabled
                        title="شما اجازه تخصیص این سفارش را ندارید">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                    تخصیص کاربر
                </button>
            @endif

            {{-- ✅ دکمه تغییر وضعیت --}}
            @if($can['changeStatus'])
                <button wire:click="openStatusModal" class="btn">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    تغییر وضعیت
                </button>
            @else
                <button class="btn" disabled title="شما اجازه تغییر وضعیت این سفارش را ندارید">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    تغییر وضعیت
                </button>
            @endif

            @if($can['approve'] && $wo->is_pending_approval)
                <button wire:click="approve" class="btn btn--primary"
                        wire:confirm="از تایید مطمئن هستید؟">
                    <svg class="icon icon--sm" ...><path d="M20 6 9 17l-5-5"/></svg>
                    تایید سفارش
                </button>
                <button wire:click="$set('showRejectModal', true)" class="btn"
                        style="color:#dc2626">
                    رد
                </button>
            @endif
        </div>
    </div>

    {{-- ==================== Grid Layout ==================== --}}
    <div class="wo-detail-grid">

        {{-- ============ ستون اصلی ============ --}}
        <div class="wo-detail-main">

            {{-- توضیحات --}}
            <div class="card">
                <div class="card__head"><h3>توضیحات</h3></div>
                <div class="card__body">
                    <p style="white-space:pre-wrap;line-height:1.8">
                        {{ $wo->description ?: '— توضیحی وارد نشده —' }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card__head">
                    <h3>
                        فایل‌های ضمیمه
                        <span class="badge badge--info" style="margin-inline-start:8px">
                {{ $this->attachments->count() }}
            </span>
                    </h3>
                </div>
                <div class="card__body">

                    {{-- آپلود سریع --}}
                    @if($can['manageChecklist'])
                        {{-- ... بدون تغییر --}}
                    @endif

                    {{-- لیست فایل‌ها --}}
                    @forelse($this->attachments as $att)

                        @php
                            $downloadUrl = url()->to('/dashboard/work-orders/attachments/' . $att->id . '/download');
                        @endphp

                        <div class="attachment-item" wire:key="att-{{ $att->id }}">

                            @if($att->is_image)
                                <a href="{{ $downloadUrl }}" class="attachment-item__thumb">
                                    <img src="{{ $downloadUrl }}"
                                         alt="{{ $att->original_name }}"
                                         loading="lazy">
                                </a>
                            @else
                                <span class="attachment-item__icon attachment-item__icon--{{ $att->icon }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.7" stroke-linecap="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                        </svg>
                    </span>
                            @endif

                            <div class="attachment-item__info">
                                <a href="{{ $downloadUrl }}" class="attachment-item__name">
                                    {{ $att->original_name }}
                                </a>
                                <div class="attachment-item__meta">
                                    {{ $att->size_human }}
                                    • {{ $att->uploader?->name ?? '—' }}
                                    • {{ verta($att->created_at)->format('%d %B - H:i') }}
                                </div>
                                @if($att->status_history_id)
                                    <span class="badge badge--info" style="font-size:10px;margin-top:4px">
                            ضمیمه تغییر وضعیت
                        </span>
                                @endif
                            </div>

                            <div class="attachment-item__actions">
                                <a href="{{ $downloadUrl }}" class="icon-btn icon-btn--sm" title="دانلود">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 3v12"/>
                                        <path d="m7 11 5 5 5-5"/>
                                        <path d="M4 21h16"/>
                                    </svg>
                                </a>

                                @if($can['manageChecklist'])
                                    <button type="button"
                                            class="icon-btn icon-btn--sm"
                                            style="color:#dc2626"
                                            wire:click="deleteAttachment({{ $att->id }})"
                                            wire:confirm="این فایل حذف شود؟"
                                            title="حذف">
                                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.7" stroke-linecap="round">
                                            <path d="M18 6 6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2);font-size:13px">
                            فایلی ضمیمه نشده.
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- ✅ چک‌لیست --}}
            <div class="card">
                <div class="card__head">
                    <h3>
                        چک‌لیست
                        <span class="badge badge--info" style="margin-inline-start:8px">
                            {{ $wo->progress }}%
                        </span>

                        @unless($can['manageChecklist'])
                            <span class="badge badge--warning"
                                  style="margin-inline-start:8px;font-size:10px">
                                فقط خواندنی
                            </span>
                        @endunless
                    </h3>
                </div>
                <div class="card__body">

                    {{-- پیام راهنما برای سازنده --}}
                    @unless($can['manageChecklist'])
                        <div style="margin-bottom:12px;padding:10px;background:#eff6ff;
                                    border-inline-start:3px solid #3b82f6;border-radius:8px;
                                    font-size:13px;color:#1e40af">
                            مدیریت چک‌لیست تنها توسط مسئول انجام سفارش یا مدیر واحد امکان‌پذیر است.
                        </div>
                    @endunless

                    {{-- فرم افزودن — فقط مجاز --}}
                    @if($can['manageChecklist'])
                        <div class="d-flex gap-2" style="margin-bottom:12px">
                            <div class="global-search" style="flex:1">
                                <input type="text"
                                       wire:model="newChecklistTitle"
                                       wire:keydown.enter="addChecklistItem"
                                       placeholder="آیتم جدید...">
                            </div>
                            <button type="button" style="margin-top: 10px"
                                    class="btn btn--primary btn--sm"
                                    wire:click="addChecklistItem">
                                افزودن
                            </button>
                        </div>

                        @error('newChecklistTitle')
                        <span class="field-error" style="display:block;margin-bottom:8px">{{ $message }}</span>
                        @enderror
                    @endif

                    {{-- آیتم‌ها --}}
                    @forelse($wo->checklistItems as $item)
                        <div class="checklist-item {{ $item->is_done ? 'is-done' : '' }}"
                             wire:key="chk-{{ $item->id }}">

                            <label style="display:flex;align-items:center;gap:10px;
                                          cursor:{{ $can['manageChecklist'] ? 'pointer' : 'default' }};
                                          flex:1;min-width:0">
                                <input type="checkbox"
                                       class="checkbox"
                                       @if($can['manageChecklist'])
                                           wire:click="toggleChecklistItem({{ $item->id }})"
                                       @else
                                           disabled
                                    @endif
                                    @checked($item->is_done)>
                                <span style="flex:1">{{ $item->title }}</span>
                            </label>

                            @if($item->is_done && $item->doneBy)
                                <small style="color:var(--color-text-2);font-size:11px">
                                    {{ $item->doneBy->name }}
                                    • {{ verta($item->done_at)->format('%d %B') }}
                                </small>
                            @endif

                            {{-- ✅ دکمه حذف — فقط مجاز --}}
                            @if($can['manageChecklist'])
                                <button type="button"
                                        class="icon-btn icon-btn--sm"
                                        wire:click="removeChecklistItem({{ $item->id }})"
                                        wire:confirm="حذف این آیتم؟"
                                        style="color:#dc2626">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M18 6 6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2)">
                            هنوز آیتمی اضافه نشده.
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- تاریخچه وضعیت (بدون تغییر — همه می‌بینن) --}}
            <div class="card">
                <div class="card__head"><h3>تاریخچه وضعیت</h3></div>
                <div class="card__body">
                    @forelse($wo->statusHistories as $h)
                        <div class="timeline-item" wire:key="h-{{ $h->id }}">
                            <div class="timeline-item__dot"></div>
                            <div class="timeline-item__body">
                                <div>
                                    @if($h->fromStatus)
                                        <span class="badge {{ $h->fromStatus->badge_class }}">
                                            {{ $h->fromStatus->label }}
                                        </span>
                                        <span style="margin:0 6px">→</span>
                                    @endif
                                    <span class="badge {{ $h->toStatus?->badge_class }}">
                                        {{ $h->toStatus?->label }}
                                    </span>
                                </div>
                                <div style="font-size:12px;color:var(--color-text-2);margin-top:4px">
                                    توسط {{ $h->changedBy?->name ?? '—' }}
                                    • {{ verta($h->created_at)->format('%d %B %Y - H:i') }}
                                </div>
                                @if($h->note)
                                    <div style="margin-top:4px;font-size:13px">«{{ $h->note }}»</div>
                                @endif

                                @if($h->attachments->count())
                                    <div class="history-attachments">
                                        @foreach($h->attachments as $att)
                                            <a href="{{ route('work-orders.attachments.download', ['attachment' => $att->id]) }}"
                                               class="history-attachment">
                                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="1.7" stroke-linecap="round" style="width:14px;height:14px">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                    <path d="M14 2v6h6"/>
                                                </svg>
                                                {{ $att->original_name }}
                                                <span style="opacity:.7;font-size:10px">({{ $att->size_human }})</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2)">
                            تاریخچه‌ای موجود نیست.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- تاریخچه تخصیص (بدون تغییر) --}}
            <div class="card">
                <div class="card__head"><h3>تاریخچه تخصیص</h3></div>
                <div class="card__body">
                    @forelse($wo->assignments as $a)
                        <div class="assign-history" wire:key="a-{{ $a->id }}">
                            <div style="display:flex;align-items:center;gap:8px">
                                <span class="avatar avatar--sm avatar--blue">
                                    {{ getInitials($a->assignedTo?->name ?? '?', '') }}
                                </span>
                                <div>
                                    <div style="font-weight:600">{{ $a->assignedTo?->name ?? '—' }}</div>
                                    <div style="font-size:11px;color:var(--color-text-2)">
                                        از {{ $a->fromDepartment?->name ?? '—' }}
                                        به {{ $a->toDepartment?->name ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            <div style="text-align:left;font-size:12px;color:var(--color-text-2)">
                                <div>{{ verta($a->assigned_at)->format('%d %B - H:i') }}</div>
                                @if($a->unassigned_at)
                                    <div style="color:#dc2626">
                                        پایان: {{ verta($a->unassigned_at)->format('%d %B - H:i') }}
                                    </div>
                                @else
                                    <span class="badge badge--success" style="font-size:10px">فعال</span>
                                @endif
                                <div style="margin-top:2px">توسط {{ $a->assignedBy?->name ?? '—' }}</div>
                            </div>
                        </div>

                        @if($a->note)
                            <div style="margin:4px 0 12px 40px;padding:8px;background:#f9fafb;
                                        border-radius:6px;font-size:13px">
                                «{{ $a->note }}»
                            </div>
                        @endif
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2)">
                            تاریخچه تخصیصی موجود نیست.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ============ ستون کناری ============ --}}
        <div class="wo-detail-side">

            {{-- اطلاعات کلی (بدون تغییر) --}}
            <div class="card">
                <div class="card__head"><h3>اطلاعات</h3></div>
                <div class="card__body">
                    <dl class="info-list">
                        <dt>واحد</dt>
                        <dd>{{ $wo->department?->name ?? '—' }}</dd>

                        <dt>مسئول فعلی</dt>
                        <dd>{{ $wo->assignee?->name ?? '—' }}</dd>

                        <dt>ایجاد کننده</dt>
                        <dd>{{ $wo->creator?->name ?? '—' }}</dd>

                        <dt>تخصیص دهنده</dt>
                        <dd>{{ $wo->assignedBy?->name ?? '—' }}</dd>

                        <dt>سررسید</dt>
                        <dd>{{ $wo->due_date ? verta($wo->due_date)->format('%d %B %Y') : '—' }}</dd>

{{--                        <dt>شروع</dt>--}}
{{--                        <dd>{{ $wo->started_at ? verta($wo->started_at)->format('%d %B - H:i') : '—' }}</dd>--}}

                        <dt>پایان</dt>
                        <dd>{{ $wo->completed_at ? verta($wo->completed_at)->format('%d %B - H:i') : '—' }}</dd>

{{--                        <dt>ساعت تخمینی</dt>--}}
{{--                        <dd>{{ $wo->estimated_hours ? $wo->estimated_hours . ' ساعت' : '—' }}</dd>--}}

                        <dt>وضعیت تایید</dt>
                        <dd>
                            @if($wo->is_pending_approval)
                                <span class="badge badge--orange">در انتظار تایید</span>
                            @elseif($wo->is_approved)
                                <span class="badge badge--success">تایید شده</span>
                                @if($wo->approvedBy)
                                    <small style="color:var(--color-text-2)">
                                        توسط {{ $wo->approvedBy->name }} • {{ verta($wo->approved_at)->format('%d %B') }}
                                    </small>
                                @endif
                            @else
                                <span class="badge badge--danger">رد شده</span>
                                @if($wo->rejection_reason)
                                    <div style="margin-top:6px;padding:8px;background:#fee2e2;border-radius:6px;font-size:12px">
                                        <strong>دلیل:</strong> {{ $wo->rejection_reason }}
                                    </div>
                                @endif
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- ✅ ساعات واقعی --}}
            <div class="card">
                <div class="card__head"><h3>ساعات واقعی</h3></div>
                <div class="card__body">
                    @if($can['updateActualHours'])
                        <div class="d-flex gap-2">
                            <div class="global-search" style="flex:1">
                                <input type="number" step="0.25" min="0" dir="ltr"
                                       wire:model="actualHours" placeholder="0">
                            </div>
                            <button style="margin: 10px" type="button" class="btn btn--primary btn--sm"
                                    wire:click="saveActualHours">ذخیره</button>
                        </div>
                        @error('actualHours') <span class="field-error">{{ $message }}</span> @enderror
                    @else
                        <div style="padding:12px;background:#f9fafb;border-radius:8px;text-align:center">
                            <div style="font-size:24px;font-weight:700;color:#1f2937">
                                {{ $wo->actual_hours ?? '—' }}
                                @if($wo->actual_hours)
                                    <span style="font-size:14px;color:#6b7280">ساعت</span>
                                @endif
                            </div>
                            <small style="color:var(--color-text-2);font-size:12px;
                                          display:block;margin-top:6px">
                                ثبت ساعات واقعی تنها توسط مسئول انجام امکان‌پذیر است.
                            </small>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ==================== Modal: تخصیص ==================== --}}
    @if($showAssignModal)
        <div class="modal-backdrop" wire:click.self="closeAssignModal">
            <div class="modal">
                <div class="modal-head">
                    <div><h2>تخصیص کاربر</h2></div>
                    <button type="button" class="icon-btn" wire:click="closeAssignModal">✕</button>
                </div>

                <form wire:submit="assign">
                    <div class="modal-body">
                        <div class="form-grid">

                            {{-- کاربر مسئول — فقط اعضای واحد فعلی --}}
                            <div class="field @error('assignToUserId') has-error @enderror"
                                 style="grid-column: span 2">
                                <label>کاربر مسئول <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model="assignToUserId">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->departmentMembers as $u)
                                            <option value="{{ $u->id }}">
                                                {{ $u->name ?? $u->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small style="color:var(--color-text-2);font-size:11px">
                                    فقط اعضای واحد مقصد قابل انتخاب هستند
                                </small>
                                @error('assignToUserId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            {{-- واحد مقصد --}}
                            <div class="field @error('assignToDeptId') has-error @enderror"
                                 style="grid-column: span 2">
                                <label>واحد مقصد <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model.live="assignToDeptId" disabled>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->departmentsList as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assignToDeptId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            {{-- یادداشت --}}
                            <div class="field" style="grid-column: span 2">
                                <label>یادداشت (اختیاری)</label>
                                <div class="global-search" style="width:100%">
                                    <textarea wire:model="assignNote" rows="3"
                                              placeholder="توضیح درباره تخصیص..."></textarea>
                                </div>
                            </div>


{{--                            <div class="field" style="grid-column: span 2">--}}
{{--                                <label>فایل‌های ضمیمه (اختیاری)</label>--}}

{{--                                <label class="file-drop file-drop--sm">--}}
{{--                                    <input type="file"--}}
{{--                                           wire:model="statusAttachments"--}}
{{--                                           multiple--}}
{{--                                           style="display:none"--}}
{{--                                           accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt,.csv">--}}

{{--                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"--}}
{{--                                         stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>--}}
{{--                                        <path d="m17 8-5-5-5 5"/>--}}
{{--                                        <path d="M12 3v12"/>--}}
{{--                                    </svg>--}}
{{--                                    <span>افزودن فایل</span>--}}
{{--                                </label>--}}

{{--                                @if(! empty($statusAttachments))--}}
{{--                                    <div class="file-previews">--}}
{{--                                        @foreach($statusAttachments as $i => $file)--}}
{{--                                            <div class="file-preview" wire:key="sa-{{ $i }}-{{ $file->getFilename() }}">--}}
{{--                                                @if(str_starts_with($file->getMimeType() ?? '', 'image/'))--}}
{{--                                                    <img src="{{ $file->temporaryUrl() }}" class="file-preview__thumb">--}}
{{--                                                @else--}}
{{--                                                    <span class="file-preview__icon">--}}
{{--                                                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"--}}
{{--                                                             stroke-width="1.7" stroke-linecap="round">--}}
{{--                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>--}}
{{--                                                            <path d="M14 2v6h6"/>--}}
{{--                                                        </svg>--}}
{{--                                                    </span>--}}
{{--                                                @endif--}}
{{--                                                <div class="file-preview__info">--}}
{{--                                                    <div class="file-preview__name">{{ $file->getClientOriginalName() }}</div>--}}
{{--                                                    <div class="file-preview__size">{{ number_format($file->getSize() / 1024, 1) }} KB</div>--}}
{{--                                                </div>--}}
{{--                                                <button type="button" class="file-preview__remove"--}}
{{--                                                        wire:click="removeStatusAttachment({{ $i }})">✕</button>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                @error('statusAttachments') <span class="field-error">{{ $message }}</span> @enderror--}}
{{--                                @error('statusAttachments.*') <span class="field-error">{{ $message }}</span> @enderror--}}
{{--                            </div>--}}

                        </div>
                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeAssignModal">انصراف</button>
                        <button type="submit" class="btn btn--primary">تخصیص</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ==================== Modal: تغییر وضعیت ==================== --}}
    @if($showStatusModal)
        <div class="modal-backdrop" wire:click.self="closeStatusModal">
            <div class="modal">
                <div class="modal-head">
                    <div><h2>تغییر وضعیت</h2></div>
                    <button type="button" class="icon-btn" wire:click="closeStatusModal">✕</button>
                </div>

                <form wire:submit="changeStatus">
                    <div class="modal-body">
                        <div class="form-grid">

                            <div class="field @error('newStatusId') has-error @enderror"
                                 style="grid-column: span 2">
                                <label>وضعیت جدید <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model="newStatusId">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->statusesList() as $s)
                                            <option value="{{ $s->id }}">{{ $s->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('newStatusId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field" style="grid-column: span 2">
                                <label>توضیح انجام کار</label>
                                <div class="global-search" style="width:100%">
                                    <textarea required wire:model="statusNote"  style="height: 60px; resize: none;"
                                              placeholder="دلیل تغییر وضعیت..."></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeStatusModal">انصراف</button>
                        <button type="submit" class="btn btn--primary">تغییر وضعیت</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
