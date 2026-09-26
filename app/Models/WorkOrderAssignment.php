<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderAssignment extends Model
{
    protected $fillable = [
        'work_order_id', 'assigned_to', 'assigned_by',
        'from_department_id', 'to_department_id',
        'note', 'assigned_at', 'unassigned_at',
    ];

    protected $casts = [
        'assigned_at'   => 'datetime',
        'unassigned_at' => 'datetime',
    ];

    public function workOrder(): BelongsTo {
        return $this->belongsTo(WorkOrder::class);
    }
    public function assignedTo(): BelongsTo{
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function assignedBy(): BelongsTo{
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function fromDepartment(): BelongsTo{
        return $this->belongsTo(Department::class, 'from_department_id');
    }
    public function toDepartment(): BelongsTo{
        return $this->belongsTo(Department::class, 'to_department_id');
    }
}
