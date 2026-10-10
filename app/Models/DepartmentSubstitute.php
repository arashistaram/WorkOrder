<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentSubstitute extends Model
{
    protected $fillable = [
        'user_id', 'substitute_for_id', 'department_id',
        'role', 'starts_at', 'ends_at', 'reason',
        'is_active', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function substituteFor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_for_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) return false;

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at   && $now->gt($this->ends_at))   return false;

        return true;
    }

    public function scopeActiveNow($q)
    {
        $now = now();
        return $q->where('is_active', true)
            ->where(fn($qq) => $qq->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn($qq) => $qq->whereNull('ends_at')->orWhere('ends_at', '>=', $now));
    }
}
