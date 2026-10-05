<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class WorkOrderStatusHistory extends Model
{
    protected $table = 'work_order_status_histories';

    public $timestamps = false;

    protected $fillable = [
        'work_order_id', 'from_status_id', 'to_status_id',
        'changed_by', 'note', 'created_at',
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

    public function attachments(): HasMany
    {
        return $this->hasMany(WorkOrderAttachment::class, 'status_history_id');
    }
}
