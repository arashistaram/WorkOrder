<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description', 'parent_id',
        'phone', 'location', 'is_active'
    ];

    protected $casts = ['is_active' => 'bool'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function departmentsUsers(): HasMany
    {
        return $this->hasMany(DepartmentUser::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'department_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'department_users')
            ->wherePivot('role', 'manager')
            ->wherePivot('is_active', true);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('is_active', true);
    }
}
