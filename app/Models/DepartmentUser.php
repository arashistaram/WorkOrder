<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Query\Builder;

class DepartmentUser extends Pivot
{
    protected $table = 'department_users';
    public const string ROLE_MANAGER = 'manager';
    public const string ROLE_USER     = 'user';

    protected $fillable = [
        'department_id',
        'user_id',
        'role',
        'is_active',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'is_active'  => 'bool',
        'joined_at'  => 'date',
        'left_at'    => 'date',
    ];

    protected $attributes = [
        'role'      => self::ROLE_USER,
        'is_active' => true,
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSupervisor(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }


    public function isMember(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function canAssignWorkOrders(): bool
    {
        return $this->isSupervisor() || $this->isDeputy();
    }
    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->left_at && $this->left_at->isPast()) {
            return false;
        }
        return true;
    }


    public function promoteToSupervisor(): self
    {
        $this->update(['role' => self::ROLE_MANAGER]);
        return $this;
    }

    public function demoteToMember(): self
    {
        $this->update(['role' => self::ROLE_USER]);
        return $this;
    }

    public function deactivate(): self
    {
        $this->update([
            'is_active' => false,
            'left_at'   => $this->left_at ?? now(),
        ]);
        return $this;
    }

    public function reactivate(): self
    {
        $this->update([
            'is_active' => true,
            'left_at'   => null,
        ]);
        return $this;
    }


    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('left_at')
                    ->orWhere('left_at', '>', now());
            });
    }

    public function scopeInactive(Builder $q): Builder
    {
        return $q->where('is_active', false);
    }

    public function scopeSupervisors(Builder $q): Builder
    {
        return $q->where('role', self::ROLE_MANAGER);
    }

    public function scopeMembers(Builder $q): Builder
    {
        return $q->where('role', self::ROLE_USER);
    }

    public function scopeWithAssignPermission(Builder $q): Builder
    {
        return $q->where('role', self::ROLE_MANAGER);
    }

    public function scopeForDepartment(Builder $q, int $departmentId): Builder
    {
        return $q->where('department_id', $departmentId);
    }

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopePrimary(Builder $q): Builder
    {
        return $q->where('is_primary', true);
    }

}
