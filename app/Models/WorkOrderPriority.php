<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderPriority extends Model
{
    protected $fillable = [
        'key', 'label', 'color', 'weight',
        'is_default', 'sort_order'
    ];

    protected $casts = ['is_default' => 'bool'];

    public function workOrders() {
        return $this->hasMany(WorkOrder::class, 'priority_id');
    }
}
