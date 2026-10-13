<div id="view" style="padding: 50px 50px">

    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">⏳ تایید سفارش‌های کار</h1>
            <p class="page-sub">
                @if($tab === 'manager')
                    سفارش‌های در انتظار تایید شما به عنوان مدیر واحد
                @else
                    سفارش‌های در انتظار تایید نهایی مدیرعامل
                @endif
            </p>
        </div>
    </div>

    {{-- Tabs --}}
    @php
        $user = auth()->user();
        $showTabs = $user->isAdmin() && (
            $user->effectiveManagedDepartmentIds()->isNotEmpty()
            || $user->effectiveSupervisedDepartmentIds()->isNotEmpty()
        );
    @endphp

    @if($showTabs)
        <div style="display:flex;gap:8px;margin-bottom:16px">
            <button class="btn {{ $tab === 'manager' ? 'btn--primary' : 'btn--ghost' }}"
                    wire:click="switchTab('manager')">
                تایید مدیر واحد
            </button>
            <button class="btn {{ $tab === 'admin' ? 'btn--primary' : 'btn--ghost' }}"
                    wire:click="switchTab('admin')">
                تایید نهایی (مدیرعامل)
            </button>
        </div>
    @endif

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
                <th>اقدامات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->pendingOrders as $wo)
                <tr wire:key="wo-{{ $wo->id }}">
                    <td style="direction:ltr">{{ $wo->code }}</td>
                    <td>
                        <a href="{{ route('work-orders.detail', $wo->id) }}"
                           wire:navigate.hover style="color:inherit;text-decoration:none">
                            {{ $wo->title }}
                        </a>
                    </td>
                    <td>{{ $wo->department?->name ?? '—' }}</td>
                    <td>{{ $wo->creator?->name ?? '—' }}</td>
                    <td>
                        @if($wo->priority)
                            <span class="badge {{ $wo->priority->badge_class }}">
                                {{ $wo->priority->label }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $wo->due_date ? verta($wo->due_date)->format('%d %B') : '—' }}</td>
                    <td>{{ verta($wo->created_at)->format('%d %B - H:i') }}</td>
                    <td class="cell-actions">
                        @php
                            $user = auth()->user();
                            $canApproveThis =
                                $user->isEffectiveManagerOf($wo->department_id)
                                || $user->isEffectiveSupervisorOf($wo->department_id);
                            $isMyCreation = $wo->created_by === $user->id;
                        @endphp

                        <div style="display:flex;gap:6px;">

                            @if($tab === 'manager')

                                @if($canApproveThis)
                                    <button type="button"
                                            class="btn btn--primary btn--sm"
                                            wire:click="openApproveModal({{ $wo->id }})">
                                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                        تایید و انتخاب واحد
                                    </button>

                                    <button class="btn btn--ghost btn--sm"
                                            style="color:#dc2626"
                                            wire:click="openRejectModal({{ $wo->id }})">
                                        رد
                                    </button>

                                @elseif($isMyCreation)
                                    {{-- سرپرست سازنده → فقط پیگیری، بدون دکمه تایید --}}
                                    <span class="badge badge--info" style="font-size:11px">
                    در انتظار تایید سرپرست «{{ $wo->department?->name }}»
                </span>
                                @endif

                            @elseif($tab === 'admin')
                                <button type="button"
                                        class="btn btn--primary btn--sm"
                                        wire:click="approveAsAdmin({{ $wo->id }})"
                                        wire:confirm="تایید نهایی سفارش {{ $wo->code }}؟">
                                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    تایید نهایی
                                </button>

                                <button class="btn btn--ghost btn--sm"
                                        style="color:#dc2626"
                                        wire:click="openRejectModal({{ $wo->id }})">
                                    رد
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--color-text-2)">
                        <p>هیچ سفارشی در انتظار تایید نیست. 🎉</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $this->pendingOrders->onEachSide(1)->links('livewire.custom-pagination') }}
    </div>

    {{-- Modal: تایید مدیر واحد --}}
    @if($showApproveModal)
        <div class="modal-backdrop" wire:click.self="closeApproveModal">
            <div class="modal">
                <div class="modal-head">
                    <div>
                        <h2>تایید سفارش کار</h2>
                        <p>واحد مقصد را انتخاب و درباره نیاز به تایید مدیرعامل تصمیم بگیرید.</p>
                    </div>
                    <button type="button" class="icon-btn" wire:click="closeApproveModal">✕</button>
                </div>

                <form wire:submit="approveAsManager">
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
                            @error('approveToDepartmentId')
                            <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field" style="margin-top:16px">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                                <input type="checkbox"
                                       wire:model.live="needsAdminApproval"
                                       class="checkbox">
                                <span style="font-weight:600">
                                    این سفارش نیاز به تایید مدیرعامل دارد
                                </span>
                            </label>

                            <div style="margin-top:8px;padding:10px;border-radius:8px;font-size:12px;
                                        background:{{ $needsAdminApproval ? '#fef3c7' : '#dcfce7' }};
                                        color:{{ $needsAdminApproval ? '#92400e' : '#166534' }};
                                        border-inline-start:3px solid {{ $needsAdminApproval ? '#f59e0b' : '#22c55e' }}">
                                @if($needsAdminApproval)
                                    ⚠️ پس از تایید شما، این سفارش برای <strong>تایید نهایی</strong>
                                    به مدیرعامل ارجاع داده می‌شود.
                                @else
                                    ✅ با تایید شما، سفارش <strong>نهایی</strong> شده و
                                    می‌توانید آن را به کاربران تخصیص دهید.
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeApproveModal">انصراف</button>
                        <button type="submit" class="btn btn--primary">
                            {{ $needsAdminApproval ? 'تایید و ارجاع به مدیرعامل' : 'تایید نهایی' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal: رد --}}
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
