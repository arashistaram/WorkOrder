<div id="view" style="padding:50px">

    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">👥 جانشین‌های من</h1>
            <p class="page-sub">
                در بازه‌های عدم حضور، دسترسی‌های سرپرستی خود را به یک همکار واحد بسپارید.
            </p>
        </div>
        <button wire:click="openModal" class="btn btn--primary">
            افزودن جانشین
        </button>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>جانشین</th>
                <th>واحد</th>
                <th>نقش</th>
                <th>از</th>
                <th>تا</th>
                <th>وضعیت</th>
                <th>اقدامات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($this->mySubstitutes as $s)
                @php $activeNow = $s->isCurrentlyValid(); @endphp
                <tr wire:key="sub-{{ $s->id }}">
                    <td>{{ $s->user?->name }}</td>
                    <td>{{ $s->department?->name }}</td>
                    <td>
                        <span class="badge badge--info">
                            {{ $s->role === 'manager' ? 'مدیر' : 'سرپرست' }}
                        </span>
                    </td>
                    <td>{{ $s->starts_at ? verta($s->starts_at)->format('Y/m/d') : '—' }}</td>
                    <td>{{ $s->ends_at   ? verta($s->ends_at)->format('Y/m/d')   : 'نامحدود' }}</td>
                    <td>
                        @if($activeNow)
                            <span class="badge badge--success">فعال</span>
                        @elseif(! $s->is_active)
                            <span class="badge">غیرفعال</span>
                        @else
                            <span class="badge badge--orange">خارج بازه</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn--sm"
                                wire:click="toggle({{ $s->id }})">
                            {{ $s->is_active ? 'غیرفعال کن' : 'فعال کن' }}
                        </button>
                        <button class="btn btn--sm"
                                style="color:#dc2626"
                                wire:click="delete({{ $s->id }})"
                                wire:confirm="حذف شود؟">
                            حذف
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:30px;color:var(--color-text-2)">
                        جانشینی ثبت نشده است.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="modal-backdrop" wire:click.self="closeModal">
            <div class="modal">
                <div class="modal-head">
                    <h2>تعیین جانشین</h2>
                    <button class="icon-btn" wire:click="closeModal">✕</button>
                </div>

                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="form-grid">

                            <div class="field" style="grid-column:span 2">
                                <label>واحد <span class="req">*</span></label>
                                <select wire:model.live="department_id">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($this->myDepartments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field" style="grid-column:span 2">
                                <label>جانشین <span class="req">*</span></label>
                                <select wire:model="user_id" @disabled(! $department_id)>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($this->departmentMembers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                <small style="font-size:11px;color:var(--color-text-2)">
                                    فقط اعضای فعال همان واحد
                                </small>
                                @error('user_id') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="field">
                                <label>نقش جانشینی</label>
                                <select wire:model="role">
                                    <option value="manager">مدیر</option>
                                </select>
                            </div>

                            <div class="field">
                                <label>از تاریخ (شمسی)</label>
                                <input type="text" wire:model="starts_at" placeholder="1404/01/15">
                            </div>

                            <div class="field">
                                <label>تا تاریخ (شمسی)</label>
                                <input type="text" wire:model="ends_at" placeholder="خالی = نامحدود">
                            </div>

                            <div class="field" style="grid-column:span 2">
                                <label>دلیل (اختیاری)</label>
                                <textarea wire:model="reason" rows="2"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-foot">
                        <span class="spacer"></span>
                        <button type="button" class="btn" wire:click="closeModal">انصراف</button>
                        <button type="submit" class="btn btn--primary">ثبت جانشین</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
