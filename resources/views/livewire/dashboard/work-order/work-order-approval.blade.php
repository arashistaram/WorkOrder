<div id="view" style="padding: 50px 50px">

    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">⏳ تایید سفارش‌های کار</h1>
            <p class="page-sub">سفارش‌های در انتظار تایید واحد شما</p>
        </div>
    </div>

    <div class="toolbar d-flex align-items-center flex-wrap gap-2">
        <div class="toolbar-search flex-grow-1">
            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.9" stroke-linecap="round">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/>
            </svg>
            <input type="search" wire:model.live.debounce.400ms="search"
                   placeholder="جستجو بر اساس کد، عنوان...">
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table">
                <thead>
                <tr>
                    <th>کد</th>
                    <th>عنوان</th>
                    <th>واحد</th>
                    <th>سازنده</th>
                    <th>اولویت</th>
                    <th>سررسید</th>
                    <th>تاریخ درخواست</th>
                    <th class="cell-actions">اقدامات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($this->pendingOrders as $wo)
                    <tr wire:key="wo-{{ $wo->id }}">
                        <td class="wo-link" style="direction:ltr">{{ $wo->code }}</td>

                        <td>
                            <a href="{{ route('work-orders.detail', $wo->id) }}"
                               wire:navigate.hover
                               style="color:inherit;text-decoration:none">
                                {{ $wo->title }}
                            </a>
                        </td>

                        <td>{{ $wo->department?->name ?? '—' }}</td>

                        <td>
                            <span class="avatar-stack">
                                <span class="avatar avatar--sm avatar--blue">
                                    {{ getInitials($wo->creator?->name ?? '?', '') }}
                                </span>
                                <span class="name">{{ $wo->creator?->name ?? '—' }}</span>
                            </span>
                        </td>

                        <td>
                            @if($wo->priority)
                                <span class="badge {{ $wo->priority->badge_class }}">
                                    <span class="badge__dot"></span>{{ $wo->priority->label }}
                                </span>
                            @endif
                        </td>

                        <td>{{ $wo->due_date ? verta($wo->due_date)->format('%d %B') : '—' }}</td>

                        <td>{{ verta($wo->created_at)->format('%d %B - H:i') }}</td>

                        <td class="cell-actions">
                            <div style="display:flex;gap:6px">
                                <button type="button"
                                        class="btn btn--primary btn--sm"
                                        wire:click="openApproveModal({{ $wo->id }})">
                                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    تایید
                                </button>

                                <button type="button"
                                        class="btn btn--ghost btn--sm"
                                        style="color:#dc2626"
                                        wire:click="openRejectModal({{ $wo->id }})">
                                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M18 6 6 18M6 6l12 12"/>
                                    </svg>
                                    رد
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:var(--color-text-2)">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.5" style="width:48px;height:48px;opacity:.3">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <p>هیچ سفارشی در انتظار تایید نیست. 🎉</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $this->pendingOrders->onEachSide(1)->links('livewire.custom-pagination') }}
        </div>
    </div>

    {{-- Modal Approve --}}
    @if($showApproveModal)
        <div class="modal-backdrop" wire:click.self="closeApproveModal">
            <div class="modal">
                <div class="modal-head">
                    <div>
                        <h2>تایید سفارش کار</h2>
                        <p>واحد مقصد را بررسی و در صورت نیاز تغییر دهید.</p>
                    </div>
                    <button type="button" class="icon-btn" wire:click="closeApproveModal">✕</button>
                </div>

                <form wire:submit="approve">
                    <div class="modal-body">
                        <div class="field @error('approveToDepartmentId') has-error @enderror">
                            <label>واحد مقصد <span class="req">*</span></label>
                            <div class="global-search" style="width:100%">
                                <select wire:model="approveToDepartmentId">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($this->managerDepartments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small style="color:var(--color-text-2);font-size:11px">
                                تنها واحدهایی که شما مدیر آن‌ها هستید قابل انتخاب هستند.
                            </small>
                            @error('approveToDepartmentId')
                            <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeApproveModal">انصراف</button>
                        <button type="submit" class="btn btn--primary">تایید و ارسال</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Reject --}}
    @if($showRejectModal)
        <div class="modal-backdrop" wire:click.self="closeRejectModal">
            <div class="modal">
                <div class="modal-head">
                    <div>
                        <h2>رد سفارش کار</h2>
                        <p>دلیل رد را وارد کنید تا به سازنده اطلاع داده شود.</p>
                    </div>
                    <button type="button" class="icon-btn" wire:click="closeRejectModal">✕</button>
                </div>

                <form wire:submit="reject">
                    <div class="modal-body">
                        <div class="field @error('rejectionReason') has-error @enderror">
                            <label>دلیل رد <span class="req">*</span></label>
                            <div class="global-search" style="width:100%">
                                <textarea wire:model="rejectionReason" rows="4"
                                          placeholder="مثلاً: اطلاعات ناقص است..."></textarea>
                            </div>
                            @error('rejectionReason') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeRejectModal">انصراف</button>
                        <button type="submit" class="btn btn--primary"
                                style="background:#dc2626;border-color:#dc2626">
                            رد کردن
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
