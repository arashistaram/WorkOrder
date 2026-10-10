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

        if ($workOrder->is_pending_approval) {
            return $user->isManagerOf($workOrder->department_id);
        }

        if ($workOrder->assignee_id === $user->id) {
            return true;
        }
        if ($workOrder->created_by === $user->id) {
            return true;
        }

        if ($workOrder->is_rejected) {
            return false;
        }

        if ($workOrder->assignee_id === $user->id) {
            return true;
        }

        if ($workOrder->assignments()->where('assigned_to', $user->id)->exists()) {
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


    public function approve(User $user, WorkOrder $workOrder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'manager') {
            return false;
        }

        if ($workOrder->approval_status !== 0) {
            return false;
        }

        return $user->managedDepartments()
            ->where('departments.id', $workOrder->department_id)
            ->exists();
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
