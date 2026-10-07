<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiQueryLog extends Model
{
    protected $fillable = [
        'user_id', 'question', 'sql',
        'row_count', 'duration_ms',
        'is_success', 'error',
    ];

    protected $casts = [
        'is_success' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
