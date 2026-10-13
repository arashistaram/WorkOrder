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

    const string STAGE_PENDING_MANAGER = 'pending_manager';
    const string STAGE_PENDING_ADMIN   = 'pending_admin';
    const string STAGE_APPROVED        = 'approved';
    const string STAGE_REJECTED        = 'rejected';


    protected $fillable = [
        'code', 'title', 'description',
        'department_id', 'assignee_id', 'assigned_by', 'assigned_at',
        'status_id', 'priority_id',
        'approval_status', 'approval_stage', 'needs_admin_approval',
        'created_by',
        'due_date', 'started_at', 'completed_at', 'cancelled_at',
        'estimated_hours', 'actual_hours',
        'approved_by', 'approved_at',
        'manager_approved_by', 'manager_approved_at',
        'admin_approved_by', 'admin_approved_at',
        'rejected_at', 'rejection_reason',
        'metadata',
    ];

    protected $casts = [
        'approval_status'      => 'int',
        'needs_admin_approval' => 'bool',
        'assigned_at'          => 'datetime',
        'approved_at'          => 'datetime',
        'manager_approved_at'  => 'datetime',
        'admin_approved_at'    => 'datetime',
        'rejected_at'          => 'datetime',
        'started_at'           => 'datetime',
        'completed_at'         => 'datetime',
        'cancelled_at'         => 'datetime',
        'due_date'             => 'date',
        'metadata'             => 'array',
        'estimated_hours'      => 'float',
        'actual_hours'         => 'float',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function managerApprovedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function adminApprovedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'admin_approved_by');
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

    public function scopePendingApproval($q)
    {
        return $q->where('approval_status', 0);
    }

    public function scopeApproved($q)
    {
        return $q->where('approval_status', 1);
    }

    public function scopeRejected($q)
    {
        return $q->where('approval_status', 2);
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
        return $q->whereNotNull('due_date')
            ->whereNull('completed_at')
            ->where('due_date', '<', now()->toDateString());
    }

    public function getIsPendingManagerAttribute(): bool
    {
        return $this->approval_stage === self::STAGE_PENDING_MANAGER;
    }

    public function getIsPendingAdminAttribute(): bool
    {
        return $this->approval_stage === self::STAGE_PENDING_ADMIN;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->approval_stage === self::STAGE_APPROVED;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->approval_stage === self::STAGE_REJECTED;
    }

    public function getIsPendingApprovalAttribute(): bool
    {
        return in_array($this->approval_stage, [
            self::STAGE_PENDING_MANAGER,
            self::STAGE_PENDING_ADMIN,
        ], true);
    }

    public function getProgressAttribute(): int
    {
        $total = $this->checklist_total ?? $this->checklistItems()->count();
        if ($total === 0) return 0;

        $done = $this->checklist_done ?? $this->checklistItems()->where('is_done', true)->count();

        return (int) round(($done / $total) * 100);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && ! $this->status?->is_final
            && $this->due_date->lt(today());
    }


    public static function generateCode(): string
    {
        $prefix = 'WO-' . verta()->format('Ymd');
        $last = static::withTrashed()
            ->where('code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('code');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(WorkOrderAttachment::class)->latest();
    }

    public function statusAttachments(): HasMany
    {
        return $this->hasMany(WorkOrderAttachment::class)
            ->whereNotNull('status_history_id')
            ->latest();
    }

    public function getApprovalLabelAttribute(): string
    {
        return match ($this->approval_status) {
            1  => 'تایید شده',
            2  => 'رد شده',
            default => 'در انتظار تایید',
        };
    }

}
