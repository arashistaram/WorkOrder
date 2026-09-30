<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrderStatus extends Model
{
    protected $fillable = [
        'key', 'label', 'color',
        'is_default', 'is_final',
        'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_default' => 'bool',
        'is_final' => 'bool',
        'is_active' => 'bool'
    ];

    public function workOrders(): HasMany {
        return $this->hasMany(WorkOrder::class, 'status_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFinal($q)
    {
        return $q->where('is_final', true);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->color) {
            'green'  => 'badge--success',
            'red'    => 'badge--danger',
            'yellow' => 'badge--warning',
            'blue'   => 'badge--info',
            'purple' => 'badge--info',
            default  => 'badge--info',
        };
    }
}
