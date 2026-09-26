<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class WorkOrderChecklistItem extends Model
{
    protected $fillable = [
        'work_order_id',
        'title',
        'is_done',
        'done_by',
        'done_at',
        'sort_order',
    ];

    protected $casts = [
        'is_done'    => 'bool',
        'done_at'    => 'datetime',
        'sort_order' => 'int',
    ];

    protected $attributes = [
        'is_done'    => false,
        'sort_order' => 0,
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by');
    }

    public function markDone(User $user): self
    {
        if ($this->is_done) {
            return $this;
        }

        $this->update([
            'is_done' => true,
            'done_by' => $user->id,
            'done_at' => now(),
        ]);

        $this->refreshWorkOrderProgress();

        return $this;
    }

    public function markUndone(): self
    {
        if (! $this->is_done) {
            return $this;
        }

        $this->update([
            'is_done' => false,
            'done_by' => null,
            'done_at' => null,
        ]);

        $this->refreshWorkOrderProgress();

        return $this;
    }

    public function toggleFor(User $user, bool $done): self
    {
        return $done ? $this->markDone($user) : $this->markUndone();
    }

    protected function refreshWorkOrderProgress(): void
    {
        $order = $this->workOrder;
        if (! $order) {
            return;
        }

        $total = $order->checklistItems()->count();
        if ($total === 0) {
            return;
        }

        $done = $order->checklistItems()->where('is_done', true)->count();

        if ($done === $total && ! $order->status->is_final) {
            $order->update([
                'metadata' => array_merge((array) $order->metadata, [
                    'checklist_completed_at' => now()->toIso8601String(),
                ]),
            ]);
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_done ? 'انجام‌شده' : 'در انتظار';
    }

    public function getCompletedAgoAttribute(): ?string
    {
        return $this->done_at?->diffForHumans();
    }

    public function scopeDone(Builder $q): Builder
    {
        return $q->where('is_done', true);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('is_done', false);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    public function scopeForWorkOrder(Builder $q, int $workOrderId): Builder
    {
        return $q->where('work_order_id', $workOrderId);
    }
}
