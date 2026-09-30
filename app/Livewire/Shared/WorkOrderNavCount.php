<?php

namespace App\Livewire\Shared;

use App\Models\WorkOrder;
use Faker\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkOrderNavCount extends Component
{
    #[Computed]
    public function count(): int
    {
        $user = auth()->user();
        if (! $user) return 0;

        return WorkOrder::query()
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->when($user->role === 'manager', function ($q) use ($user) {
                $deptIds = $user->managedDepartments()->pluck('departments.id');
                $q->whereIn('department_id', $deptIds);
            })
            ->when($user->role === 'user', function ($q) use ($user) {
                $q->where('assignee_id', $user->id);
            })
            ->count();
    }

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.shared.work-order-nav-count');
    }
}
