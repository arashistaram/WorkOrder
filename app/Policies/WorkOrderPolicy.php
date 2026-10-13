<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;
use function Symfony\Component\Translation\t;

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

        if($user->role === 'manager'){
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
        if($user->isPrivileged()){
            return true;
        }

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'manager') {
            return false;
        }

        if ($workOrder->approval_status !== 0) {
            return false;
        }


        if ($workOrder->is_approved && $user->isMemberOf($workOrder->department_id)) {
            return true;
        }

//        $isEffectiveSupervisor = $user->isEffectiveSupervisorOf($workOrder->department_id);
//        $isEffectiveManager    = $user->isEffectiveManagerOf($workOrder->department_id);
//
//        return ($isEffectiveSupervisor || $isEffectiveManager)
//            && (int) $workOrder->approval_status === 0;

//        return $user->managedDepartments()
//            ->where('departments.id', $workOrder->department_id)
//            ->exists();

        return false;
    }

    public function approveAsManager(User $user, WorkOrder $wo): bool
    {
        if (! $wo->is_pending_manager) return false;

        return $user->isEffectiveManagerOf($wo->department_id)
            || $user->isEffectiveSupervisorOf($wo->department_id);
    }

    public function approveAsAdmin(User $user, WorkOrder $wo): bool
    {
        return $wo->is_pending_admin && $user->isAdmin();
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

        if (! $workOrder->is_approved) return false;

        return $workOrder->manager_approved_by === $user->id
            || $user->isAdmin();

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

        if (! $workOrder->is_approved) return false;


        return $workOrder->assignee_id === $user->id
            || $workOrder->manager_approved_by === $user->id
            || $user->isAdmin();
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

        if (! $workOrder->is_approved) return false;

        return $workOrder->assignee_id === $user->id
            || $workOrder->manager_approved_by === $user->id
            || $user->isAdmin();
    }

    public function updateActualHours(User $user, WorkOrder $workOrder): bool
    {
        return $this->manageChecklist($user, $workOrder);
    }
}
