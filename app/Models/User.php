<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name','username', 'password', 'role',
    'phone', 'employee_code', 'job_title',
    'is_active', 'last_login_at', 'settings'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $casts = [
        'last_login_at'     => 'datetime',
        'is_active'         => 'bool',
        'settings'          => 'array',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_users')
            ->withPivot(['role', 'is_active', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function supervisedDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_users')
            ->wherePivot('role', 'supervisor')
            ->wherePivot('is_active', true);
    }

    public function outputAccount(): HasOne
    {
        return $this->hasOne(OutputMessengerAccount::class, 'user_id');
    }

    public function createdWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'created_by');
    }

    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assignee_id');
    }

    public function isSupervisorOf(int $departmentId): bool
    {
        return $this->departments()
            ->wherePivot('department_id', $departmentId)
            ->wherePivot('role', 'supervisor')
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function isMemberOf(int $departmentId): bool
    {
        return $this->departments()
            ->wherePivot('department_id', $departmentId)
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function isManagerOf(int $departmentId): bool
    {
        return $this->departments()
            ->where('departments.id', $departmentId)
            ->wherePivot('role', 'manager')
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function activeDepartments(): BelongsToMany
    {
        return $this->departments()->wherePivot('is_active', true);
    }

    public function managedDepartments(): BelongsToMany
    {
        return $this->departments()->wherePivot('role', 'manager')
            ->wherePivot('is_active', true);
    }

}
