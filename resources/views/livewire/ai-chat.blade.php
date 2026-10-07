<div id="view" style="padding: 50px 50px">

    {{-- ==================== Page Head ==================== --}}
    <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1 class="page-title">🤖 دستیار هوشمند</h1>
            <p class="page-sub">درباره سفارش‌های کار بپرس — پاسخ بر اساس داده‌های واقعی سیستم</p>
        </div>

        <div class="page-head-actions">
            <button wire:click="clearChat"
                    class="btn btn--ghost btn--sm"
                    wire:confirm="مطمئنی می‌خوای چت رو پاک کنی؟">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"/>
                    <path d="M8 6V4h8v2"/>
                    <path d="M6 6l1 14h10l1-14"/>
                </svg>
                چت جدید
            </button>
        </div>
    </div>

    <div class="ai-chat-wrap">

        {{-- Messages Area --}}
        <div class="ai-chat-messages" id="ai-chat-scroll" wire:poll.keep-alive>

            @foreach($messages as $i => $msg)
                <div class="ai-msg ai-msg--{{ $msg['role'] }}"
                     wire:key="msg-{{ $i }}-{{ md5($msg['content'] ?? '') }}">

                    {{-- Avatar --}}
                    <div class="ai-msg__avatar">
                        @if($msg['role'] === 'user')
                            {{ getInitials(auth()->user()?->name ?? '?', '') }}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" width="20" height="20">
                                <path d="M12 2a5 5 0 0 0-5 5v3a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5z"/>
                                <path d="M4 12a8 8 0 0 0 16 0"/>
                                <path d="M12 20v2"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="ai-msg__body">
                        <div class="ai-msg__content">{!! nl2br(e($msg['content'] ?? '')) !!}</div>

                        {{-- Tool Badges --}}
                        @if(!empty($msg['meta']['tools']))
                            <div class="ai-msg__tools">
                                @foreach($msg['meta']['tools'] as $tool)
                                    <span class="ai-tool-badge"
                                          title="{{ json_encode($tool['args'] ?? [], JSON_UNESCAPED_UNICODE) }}">
                                        ⚙️ {{ $tool['name'] ?? 'tool' }}
                                    </span>
                                @endforeach

                                @if(!empty($msg['meta']['iterations']))
                                    <span class="ai-tool-badge ai-tool-badge--meta">
                                        {{ $msg['meta']['iterations'] }} مرحله
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Loading --}}
            @if($loading)
                <div class="ai-msg ai-msg--assistant">
                    <div class="ai-msg__avatar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" width="20" height="20">
                            <path d="M12 2a5 5 0 0 0-5 5v3a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5z"/>
                            <path d="M4 12a8 8 0 0 0 16 0"/>
                            <path d="M12 20v2"/>
                        </svg>
                    </div>
                    <div class="ai-msg__body">
                        <div class="ai-typing">
                            <span></span><span></span><span></span>
                        </div>
                        <small style="color:var(--color-text-2);font-size:11px;margin-top:4px;display:block">
                            در حال تحلیل درخواست...
                        </small>
                    </div>
                </div>
            @endif

        </div>

        @if(count($messages) <= 1 && ! $loading)
            <div class="ai-suggestions">
                <div class="ai-suggestions__label">پیشنهاد:</div>

                @foreach([
                    'چند سفارش کار معوق داریم؟',
                    'آمار کلی سفارش‌ها چیه؟',
                    'بار کاری هر مسئول چقدره؟',
                    'سفارش‌های در حال انجام رو نشون بده',
                    'سفارش‌های با اولویت بحرانی کدومن؟',
                ] as $s)
                    <button type="button"
                            class="ai-suggestion"
                            wire:click="$set('message', @js($s))">
                        {{ $s }}
                    </button>
                @endforeach
            </div>
        @endif

        <form wire:submit="send" class="ai-chat-input">

            <textarea
                wire:model="message"
                wire:keydown.enter.prevent="send"
                placeholder="سوالت را بنویس... (Enter ارسال، Shift+Enter خط جدید)"
                rows="1"
                dir="auto"
                @if($loading) disabled @endif
                x-data
                x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
            ></textarea>

            <button type="submit"
                    class="btn btn--primary ai-chat-input__send"
                    @if($loading || trim($message) === '') disabled @endif>
                @if($loading)
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" style="width:18px;height:18px;animation:spin 1s linear infinite">
                        <path d="M21 12a9 9 0 1 1-6.2-8.6"/>
                    </svg>
                @else
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         style="width:18px;height:18px">
                        <path d="m22 2-7 20-4-9-9-4z"/>
                    </svg>
                @endif
            </button>
        </form>

    </div>

</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Auto-scroll to bottom after each message
        Livewire.on('scroll-chat-bottom', () => {
            requestAnimationFrame(() => {
                const el = document.getElementById('ai-chat-scroll');
                if (el) el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
            });
        });

        // Initial scroll
        setTimeout(() => {
            const el = document.getElementById('ai-chat-scroll');
            if (el) el.scrollTop = el.scrollHeight;
        }, 100);
    });
</script>
