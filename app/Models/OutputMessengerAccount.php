<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutputMessengerAccount extends Model
{
    protected $fillable = [
        'user_id',
        'output_user_id',
        'output_username',
        'output_email',
        'output_mobile',
        'is_active',
        'notify_on_assign',
        'notify_on_status_change',
        'last_notified_at',
        'metadata',
    ];

    protected $casts = [
        'is_active'                 => 'boolean',
        'notify_on_assign'          => 'boolean',
        'notify_on_status_change'   => 'boolean',
        'last_notified_at'          => 'datetime',
        'metadata'                  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeReadyForAssign($q)
    {
        return $q->where('is_active', true)
            ->where('notify_on_assign', true)
            ->whereNotNull('output_user_id');
    }

    public function getRecipientIdentifierAttribute(): ?string
    {
        return $this->output_user_id ?: $this->output_email ?: $this->output_username;
    }

    public function markNotified(): void
    {
        $this->update(['last_notified_at' => now()]);
    }
}
