<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class WorkOrderStatusHistory extends Model
{
    protected $table = 'work_order_status_histories';

    public const ?string UPDATED_AT = null;

    protected $fillable = [
        'work_order_id',
        'from_status_id',
        'to_status_id',
        'changed_by',
        'note',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'from_status_id');
    }

    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'to_status_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public static function record(
        WorkOrder $workOrder,
        ?WorkOrderStatus $fromStatus,
        WorkOrderStatus $toStatus,
        User $actor,
        ?string $note = null
    ): self {
        return static::query()->create([
            'work_order_id'  => $workOrder->id,
            'from_status_id' => $fromStatus?->id,
            'to_status_id'   => $toStatus->id,
            'changed_by'     => $actor->id,
            'note'           => $note,
        ]);
    }

    public function getIsInitialAttribute(): bool
    {
        return $this->from_status_id === null;
    }

    public function getChangedAgoAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? '';
    }

    public function getSummaryAttribute(): string
    {
        $actor  = $this->changedBy?->name ?? 'سیستم';
        $from   = $this->fromStatus?->label ?? '—';
        $to     = $this->toStatus?->label ?? '—';

        if ($this->is_initial) {
            return sprintf('%s سفارش را با وضعیت «%s» ایجاد کرد.', $actor, $to);
        }

        return sprintf('%s وضعیت را از «%s» به «%s» تغییر داد.', $actor, $from, $to);
    }

    public function scopeForWorkOrder(Builder $q, int $workOrderId): Builder
    {
        return $q->where('work_order_id', $workOrderId);
    }

    public function scopeByUser(Builder $q, int $userId): Builder
    {
        return $q->where('changed_by', $userId);
    }

    public function scopeTransition(Builder $q, string $fromKey, string $toKey): Builder
    {
        return $q->whereHas('fromStatus', fn($s) => $s->where('key', $fromKey))
            ->whereHas('toStatus',   fn($s) => $s->where('key', $toKey));
    }

    public function scopeToStatus(Builder $q, string $statusKey): Builder
    {
        return $q->whereHas('toStatus', fn($s) => $s->where('key', $statusKey));
    }

    public function scopeBetween(Builder $q, $from, $to): Builder
    {
        return $q->whereBetween('created_at', [$from, $to]);
    }

    public function scopeLatestFirst(Builder $q): Builder
    {
        return $q->orderByDesc('created_at');
    }
}
