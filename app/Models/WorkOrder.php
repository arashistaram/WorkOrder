<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'title', 'description',
        'department_id', 'assignee_id', 'assigned_by', 'assigned_at',
        'customer_id', 'project_id', 'location_id', 'asset_id', 'category_id',
        'status_id', 'priority_id', 'created_by',
        'due_date', 'started_at', 'completed_at', 'cancelled_at',
        'estimated_hours', 'actual_hours', 'metadata',
    ];

    protected $casts = [
        'assigned_at'   => 'datetime',
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
        'due_date'      => 'date',
        'metadata'      => 'array',
        'estimated_hours' => 'decimal:2',
        'actual_hours'    => 'decimal:2',
    ];

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }
    public function assignee(): BelongsTo {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    public function assigner(): BelongsTo {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(WorkOrderStatus::class, 'status_id');
    }
    public function priority(): BelongsTo {
        return $this->belongsTo(WorkOrderPriority::class, 'priority_id');
    }
    public function assignments(): HasMany{
        return $this->hasMany(WorkOrderAssignment::class);
    }
    public function statusHistory(): HasMany{
        return $this->hasMany(WorkOrderStatusHistory::class);
    }

    public function statusHistories(): HasMany {
        return $this->hasMany(WorkOrderStatusHistory::class)
            ->orderByDesc('created_at');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function checklistItems(): HasMany {
        return $this->hasMany(WorkOrderChecklistItem::class)->orderBy('sort_order');
    }
    public function watchers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'work_order_watchers');
    }

    public function scopeForDepartment(Builder $q, int $departmentId): Builder
    {
        return $q->where('department_id', $departmentId);
    }

    public function scopeUnassigned(Builder $q): Builder
    {
        return $q->whereNull('assignee_id');
    }

    public function scopeAssignedTo(Builder $q, int $userId): Builder
    {
        return $q->where('assignee_id', $userId);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->whereHas('status', fn($s) => $s->where('is_final', false));
    }

    public function scopeOverdue(Builder $q): Builder
    {
        return $q->active()->whereDate('due_date', '<', now()->toDateString());
    }

    public function getProgressAttribute(): int
    {
        $total = $this->checklistItems->count();
        if ($total === 0) return 0;
        $done = $this->checklistItems->where('is_done', true)->count();
        return (int) round(($done / $total) * 100);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && ! $this->status?->is_final
            && $this->due_date->isPast();
    }


    public static function generateCode(): string
    {
        $year  = verta()->format('Y');
        $count = static::withTrashed()->whereYear('created_at', now()->year)->count() + 1;

        do {
            $code = sprintf('WO-%s-%04d', $year, $count);
            $count++;
        } while (static::withTrashed()->where('code', $code)->exists());

        return $code;
    }
}
