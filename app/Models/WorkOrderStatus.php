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
}
