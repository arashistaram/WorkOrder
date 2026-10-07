<?php

namespace App\Livewire;

use App\Services\Ai\McpAgentService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('هوش مصنوعی')]
class AiChatController extends Component
{
    public string $message = '';
    public array $messages = [];
    public bool $loading = false;

    public function mount(): void
    {
        $this->messages = [[
            'role'    => 'assistant',
            'content' => 'سلام 👋 من دستیار هوشمند سیستم سفارش کار هستم. چطور می‌تونم کمکت کنم؟',
        ]];
    }

    public function send(): void
    {
        $text = trim($this->message);
        if ($text === '') return;

        // پیام کاربر به لیست اضافه میشه
        $this->messages[] = ['role' => 'user', 'content' => $text];
        $this->message    = '';
        $this->loading    = true;

        try {
            $service = app(McpAgentService::class);

            // ✅ فقط history رو پاس بده — پیام جدید توش هست
            $history = $this->historyForModel();
            $result  = $service->chat('', $history);

            $this->messages[] = [
                'role'    => 'assistant',
                'content' => $result['content'],
                'meta'    => [
                    'tools'      => $result['tools_used'] ?? [],
                    'iterations' => $result['iterations'] ?? 0,
                ],
            ];
        } catch (\Throwable $e) {
            $this->messages[] = [
                'role'    => 'assistant',
                'content' => 'خطا: ' . $e->getMessage(),
            ];
        }

        $this->loading = false;
        $this->dispatch('scroll-chat-bottom');
    }

    public function clearChat(): void
    {
        $this->messages = [[
            'role'    => 'assistant',
            'content' => 'چت پاک شد. چطور می‌تونم کمکت کنم؟',
        ]];
    }

    protected function historyForModel(): array
    {
        $all = $this->messages;
        array_shift($all);

        return array_map(fn($m) => [
            'role'    => $m['role'],
            'content' => $m['content'] ?? '',
        ], $all);
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.ai-chat');
    }
}
