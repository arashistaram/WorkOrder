<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderPriority extends Model
{
    protected $fillable = [
        'key',
        'label',
        'color',
        'level',
        'is_active',
    ];

    protected $casts = [
        'level'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('level')->orderBy('id');
    }

    public function workOrders() {
        return $this->hasMany(WorkOrder::class, 'priority_id');
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->color) {
            'green'  => 'badge--success',
            'red'    => 'badge--danger',
            'yellow' => 'badge--warning',
            'blue', 'purple' => 'badge--info',
            default  => 'badge--info',
        };
    }
}
