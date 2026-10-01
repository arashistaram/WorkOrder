<?php

namespace App\Services;

use App\Models\OutputMessengerAccount;
use App\Models\User;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class OutputMessengerService
{
    protected function baseUrl(): string
    {
        return rtrim((string) config('output-messenger.base_url'), '/');
    }

    protected function apiKey(): string
    {
        return (string) config('output-messenger.api_key');
    }

    protected function timeout(): int
    {
        return (int) config('output-messenger.timeout', 10);
    }

    protected function enabled(): bool
    {
        return (bool) config('output-messenger.enabled')
            && $this->baseUrl() !== ''
            && $this->apiKey() !== '';
    }

    protected function http(): PendingRequest|Factory
    {
        return Http::timeout($this->timeout())
            ->withHeaders([
                'API-KEY' => $this->apiKey(),
                'Accept'  => 'application/json',
            ]);
    }

    /* =========================================================
     *  Core: POST /api/notify
     * ========================================================= */

    /**
     * ارسال نوتیفیکیشن.
     *
     * @param  string      $from     نام/عنوان فرستنده
     * @param  string      $message  متن پیام
     * @param  string|null $to       نام کاربری/ایمیل گیرنده
     * @param  string|null $room     نام اتاق (اگه to نداری)
     * @param  string      $color    رنگ پس‌زمینه
     * @param  int         $otr      0|1
     * @param  int         $notify   0|1
     */
    public function sendNotification(
        string $from,
        string $message,
        ?string $to = null,
        ?string $room = null,
        string $color = '#C7EDFC',
        int $otr = 0,
        int $notify = 1
    ): bool {
        if (! $this->enabled()) {
            Log::info('OutputMessenger: service disabled');
            return false;
        }

        if (! $to && ! $room) {
            Log::warning('OutputMessenger: notify requires to or room');
            return false;
        }

        if (trim($message) === '') {
            Log::warning('OutputMessenger: empty message');
            return false;
        }

        try {
            $query = array_filter([
                'from'    => $from,
                'to'      => $to,
                'room'    => $room,
                'message' => $message,
                'color'   => $color,
                'otr'     => $otr,
                'notify'  => $notify,
            ], fn($v) => $v !== null && $v !== '');

            $url = $this->baseUrl() . '/api/notify?' . http_build_query($query);

            $res = $this->http()->post($url);

            if (! $res->successful()) {
                Log::warning('OutputMessenger: notify failed', [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                    'to'     => $to,
                    'room'   => $room,
                ]);
                return false;
            }

            $ok = (bool) $res->json('success', false);

            if (! $ok) {
                Log::warning('OutputMessenger: notify returned success=false', [
                    'body' => $res->body(),
                ]);
            }

            return $ok;

        } catch (\Throwable $e) {
            Log::error('OutputMessenger: notify exception', [
                'msg' => $e->getMessage(),
                'to'  => $to,
            ]);
            return false;
        }
    }

    /* =========================================================
     *  Helpers — برای کار با مدل‌های داخلی
     * ========================================================= */

    /**
     * ارسال نوتیف به یک کاربر داخلی (بر اساس جدول output_messenger_accounts)
     */
    public function notifyUser(User $user, string $title, string $body = ''): bool
    {
        $account = OutputMessengerAccount::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (! $account) {
            Log::info('OutputMessenger: no active account for user', ['user_id' => $user->id]);
            return false;
        }

        return $this->notifyAccount($account, $title, $body);
    }

    /**
     * ارسال نوتیف به یک اکانت Output
     */
    public function notifyAccount(OutputMessengerAccount $account, string $title, string $body = ''): bool
    {
        $recipient = $account->output_username
            ?: $account->output_email
                ?: $account->output_user_id;

        if (! $recipient) {
            Log::warning('OutputMessenger: account has no recipient identifier', [
                'account_id' => $account->id,
            ]);
            return false;
        }

        $message = trim($title . ($body !== '' ? "\n\n" . $body : ''));

        $from = config('output-messenger.sender', 'سیستم سفارش کار');

        $ok = $this->sendNotification(
            from:    $from,
            message: $message,
            to:      $recipient,
            room:    null,
            color:   '#C7EDFC',
            otr:     0,
            notify:  1
        );

        if ($ok) {
            $account->update(['last_notified_at' => now()]);
        }

        return $ok;
    }


    public function notifyUsername(string $username, string $title, string $body = ''): bool
    {
        $message = trim($title . ($body !== '' ? "\n\n" . $body : ''));

        return $this->sendNotification(
            from:    config('output-messenger.sender', 'سیستم سفارش کار'),
            message: $message,
            to:      $username,
            notify:  1
        );
    }
}
