@php $wo = $this->workOrder; @endphp

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

        <div class="page-head-actions d-flex align-items-center gap-2">
            <a href="{{ route('work-orders') }}" wire:navigate.hover class="btn btn--ghost">
                بازگشت
            </a>

            <button wire:click="openAssignModal" class="btn btn--primary">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                تخصیص کاربر
            </button>
            <button wire:click="openStatusModal" class="btn">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                تغییر وضعیت
            </button>
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

            {{-- چک‌لیست --}}
            <div class="card">
                <div class="card__head">
                    <h3>
                        چک‌لیست
                        <span class="badge badge--info" style="margin-inline-start:8px">
                            {{ $wo->progress }}%
                        </span>
                    </h3>
                </div>
                <div class="card__body">

                    {{-- افزودن آیتم --}}
                    <div class="d-flex gap-2" style="margin-bottom:12px">
                        <div class="global-search" style="flex:1">
                            <input type="text"
                                   wire:model="newChecklistTitle"
                                   wire:keydown.enter="addChecklistItem"
                                   placeholder="آیتم جدید...">
                        </div>
                        <button type="button"
                                class="btn btn--primary btn--sm"
                                wire:click="addChecklistItem">
                            افزودن
                        </button>
                    </div>

                    @error('newChecklistTitle')
                    <span class="field-error" style="display:block;margin-bottom:8px">{{ $message }}</span>
                    @enderror

                    {{-- آیتم‌ها --}}
                    @forelse($wo->checklistItems as $item)
                        <div class="checklist-item {{ $item->is_done ? 'is-done' : '' }}"
                             wire:key="chk-{{ $item->id }}">

                            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;flex:1;min-width:0">
                                <input type="checkbox"
                                       class="checkbox"
                                       wire:click="toggleChecklistItem({{ $item->id }})"
                                    @checked($item->is_done)>
                                <span style="flex:1">{{ $item->title }}</span>
                            </label>

                            @if($item->is_done && $item->doneBy)
                                <small style="color:var(--color-text-2);font-size:11px">
                                    {{ $item->doneBy->name }}
                                    • {{ verta($item->done_at)->format('%d %B') }}
                                </small>
                            @endif

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
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2)">
                            هنوز آیتمی اضافه نشده.
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- تاریخچه وضعیت --}}
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
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:var(--color-text-2)">
                            تاریخچه‌ای موجود نیست.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- تاریخچه تخصیص --}}
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
                                    <div style="color:#dc2626">پایان: {{ verta($a->unassigned_at)->format('%d %B - H:i') }}</div>
                                @else
                                    <span class="badge badge--success" style="font-size:10px">فعال</span>
                                @endif
                                <div style="margin-top:2px">توسط {{ $a->assignedBy?->name ?? '—' }}</div>
                            </div>
                        </div>

                        @if($a->note)
                            <div style="margin:4px 0 12px 40px;padding:8px;background:#f9fafb;border-radius:6px;font-size:13px">
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

            {{-- اطلاعات کلی --}}
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

                        <dt>شروع</dt>
                        <dd>{{ $wo->started_at ? verta($wo->started_at)->format('%d %B - H:i') : '—' }}</dd>

                        <dt>پایان</dt>
                        <dd>{{ $wo->completed_at ? verta($wo->completed_at)->format('%d %B - H:i') : '—' }}</dd>

                        <dt>ساعت تخمینی</dt>
                        <dd>{{ $wo->estimated_hours ? $wo->estimated_hours . ' ساعت' : '—' }}</dd>
                    </dl>
                </div>
            </div>

            {{-- ساعات واقعی --}}
            <div class="card">
                <div class="card__head"><h3>ساعات واقعی</h3></div>
                <div class="card__body">
                    <div class="d-flex gap-2">
                        <div class="global-search" style="flex:1">
                            <input type="number" step="0.25" min="0" dir="ltr"
                                   wire:model="actualHours" placeholder="0">
                        </div>
                        <button type="button" class="btn btn--primary btn--sm"
                                wire:click="saveActualHours">ذخیره</button>
                    </div>
                    @error('actualHours') <span class="field-error">{{ $message }}</span> @enderror
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

                            <div class="field @error('assignToUserId') has-error @enderror" style="grid-column: span 2">
                                <label>کاربر مسئول <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model="assignToUserId">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->usersList as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assignToUserId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field @error('assignToDeptId') has-error @enderror" style="grid-column: span 2">
                                <label>واحد مقصد <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model="assignToDeptId">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->departmentsList as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assignToDeptId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field" style="grid-column: span 2">
                                <label>یادداشت (اختیاری)</label>
                                <div class="global-search" style="width:100%">
                                    <textarea wire:model="assignNote" rows="3"
                                              placeholder="توضیح درباره تخصیص..."></textarea>
                                </div>
                            </div>

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

                            <div class="field @error('newStatusId') has-error @enderror" style="grid-column: span 2">
                                <label>وضعیت جدید <span class="req">*</span></label>
                                <div class="global-search" style="width:100%">
                                    <select wire:model="newStatusId">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($this->statusesList as $s)
                                            <option value="{{ $s->id }}">{{ $s->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('newStatusId') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field" style="grid-column: span 2">
                                <label>یادداشت (اختیاری)</label>
                                <div class="global-search" style="width:100%">
                                    <textarea wire:model="statusNote" rows="3"
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
