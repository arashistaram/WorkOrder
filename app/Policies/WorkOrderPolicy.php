<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'user'], true);
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }


        if ($workOrder->created_by === $user->id) {
            return true;
        }


        if ($workOrder->assignee_id === $user->id) {
            return true;
        }

        if ($user->role === 'manager') {
            return $user->managedDepartments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        if ($user->role === 'user') {
            return $user->departments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager'], true);
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($workOrder->created_by === $user->id) {
            return true;
        }

        if ($user->role === 'manager') {
            return $user->managedDepartments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        return false;
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === 'admin';
    }

    public function assign(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($workOrder->created_by === $user->id) {
            return false;
        }

        if ($user->role === 'manager') {
            return $user->managedDepartments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        return false;
    }


    public function changeStatus(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($workOrder->assignee_id === $user->id) {
            return true;
        }

        if ($user->role === 'manager') {
            return $user->managedDepartments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        return false;
    }

    public function manageChecklist(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($workOrder->created_by === $user->id) {
            return false;
        }

        if ($workOrder->assignee_id === $user->id) {
            return true;
        }

        if ($user->role === 'manager') {
            return $user->managedDepartments()
                ->where('departments.id', $workOrder->department_id)
                ->exists();
        }

        return false;
    }

    public function updateActualHours(User $user, WorkOrder $workOrder): bool
    {
        return $this->manageChecklist($user, $workOrder);
    }
}
