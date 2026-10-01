<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderStatus;
use App\Models\WorkOrderStatusHistory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('داشبورد')]
final class DashboardController extends Component
{
    protected function baseQuery(): Builder|HigherOrderWhenProxy
    {
        $user = auth()->user();

        return WorkOrder::query()
            ->when($user->role === 'manager', function ($q) use ($user) {
                $deptIds = $user->managedDepartments()->pluck('departments.id');
                $q->where(function ($qq) use ($deptIds, $user) {
                    $qq->whereIn('department_id', $deptIds)
                        ->orWhere('created_by', $user->id)
                        ->orWhere('assignee_id', $user->id);
                });
            })
            ->when($user->role === 'user', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->where('assignee_id', $user->id)
                        ->orWhere('created_by', $user->id);
                });
            });

    }


    #[Computed]
    public function openCount(): int
    {
        return $this->baseQuery()
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->count();
    }

    #[Computed]
    public function openDelta(): float
    {
        $now  = $this->baseQuery()
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->count();

        $prev = $this->baseQuery()
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->where('created_at', '<', now()->subDays(30))
            ->count();

        if ($prev === 0) return $now > 0 ? 100 : 0;

        return round((($now - $prev) / $prev) * 100, 1);
    }


    #[Computed]
    public function inProgressCount(): int
    {
        return $this->baseQuery()
            ->whereHas('status', fn($q) => $q->where('key', 'in_progress'))
            ->count();
    }


    #[Computed]
    public function dueTodayCount(): int
    {
        return $this->baseQuery()
            ->whereDate('due_date', today())
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->count();
    }

    #[Computed]
    public function overdueCount(): int
    {
        return $this->baseQuery()
            ->whereDate('due_date', '<', today())
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->count();
    }



    #[Computed]
    public function completedThisMonthCount(): int
    {
        $start = verta()->startMonth()->toCarbon();
        $end   = verta()->endMonth()->toCarbon();

        return $this->baseQuery()
            ->whereHas('status', fn($q) => $q->where('key', 'completed'))
            ->whereBetween('completed_at', [$start, $end])
            ->count();
    }

    #[Computed]
    public function attentionItems()
    {
        return $this->baseQuery()
            ->with(['department', 'assignee', 'status', 'priority'])
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->where(function ($q) {
                $q->whereDate('due_date', '<', today())
                ->orWhereDate('due_date', today());
            })
            ->orderByRaw('due_date ASC')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function workload(): Collection|\Illuminate\Support\Collection
    {
        $user = auth()->user();

        $query = WorkOrder::query()
            ->select('assignee_id', DB::raw('COUNT(*) as open_count'))
            ->whereHas('status', fn($q) => $q->where('is_final', false))
            ->whereNotNull('assignee_id');

        if ($user->role === 'manager') {
            $deptIds = $user->managedDepartments()->pluck('departments.id');
            $query->where(function ($q) use ($deptIds, $user) {
                $q->whereIn('department_id', $deptIds)
                    ->orWhere('created_by', $user->id)
                    ->orWhere('assignee_id', $user->id);
            });
        } elseif ($user->role === 'user') {
            $query->where(function ($q) use ($user) {
                $q->where('assignee_id', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        $rows = $query
            ->groupBy('assignee_id')
            ->orderByDesc('open_count')
            ->limit(7)
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        $users = User::query()
            ->whereIn('id', $rows->pluck('assignee_id'))
            ->get(['id', 'name'])
            ->keyBy('id');

        $max = $rows->max('open_count') ?: 1;

        return $rows->map(fn($r) => [
            'user'       => $users->get($r->assignee_id),
            'count'      => (int) $r->open_count,
            'percentage' => (int) round(($r->open_count / $max) * 100),
        ])->filter(fn($r) => $r['user'] !== null);
    }


    #[Computed]
    public function recentActivity()
    {
        $user = auth()->user();

        $query = WorkOrderStatusHistory::query()
            ->with([
                'workOrder:id,code,title,department_id,assignee_id,created_by',
                'changedBy:id,name',
                'fromStatus:id,label,color',
                'toStatus:id,label,color',
            ])
            ->latest('created_at');

        if ($user->role === 'manager') {
            $deptIds = $user->managedDepartments()->pluck('departments.id');
            $query->whereHas('workOrder', function ($q) use ($deptIds, $user) {
                $q->where(function ($qq) use ($deptIds, $user) {
                    $qq->whereIn('department_id', $deptIds)
                        ->orWhere('created_by', $user->id)
                        ->orWhere('assignee_id', $user->id);
                });
            });
        } elseif ($user->role === 'user') {
            $query->whereHas('workOrder', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->where('assignee_id', $user->id)
                        ->orWhere('created_by', $user->id);
                });
            });
        }

        return $query->limit(8)->get();
    }


    #[Computed]
    public function statusBreakdown(): Collection|\Illuminate\Support\Collection
    {
        $user = auth()->user();

        $statuses = WorkOrderStatus::query()
            ->orderBy('id')
            ->get(['id', 'key', 'label', 'color', 'is_final']);

        $counts = WorkOrder::query()
            ->select('status_id', DB::raw('COUNT(*) as total'))
            ->when($user->role === 'manager', function ($q) use ($user) {
                $deptIds = $user->managedDepartments()->pluck('departments.id');
                $q->where(function ($qq) use ($deptIds, $user) {
                    $qq->whereIn('department_id', $deptIds)
                        ->orWhere('created_by', $user->id)
                        ->orWhere('assignee_id', $user->id);
                });
            })
            ->when($user->role === 'user', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->where('assignee_id', $user->id)
                        ->orWhere('created_by', $user->id);
                });
            })
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        $max = $counts->max() ?: 1;

        return $statuses->map(fn($s) => [
            'key'        => $s->key,
            'label'      => $s->label,
            'badge_class'=> $s->badge_class ?? $this->badgeClass($s->color),
            'count'      => (int) ($counts[$s->id] ?? 0),
            'percentage' => (int) round((($counts[$s->id] ?? 0) / $max) * 100),
        ])->filter(fn($r) => $r['count'] > 0 || true); // همه وضعیت‌ها نمایش
    }

    protected function badgeClass(string $color): string
    {
        return match ($color) {
            'green'  => 'badge--success',
            'red'    => 'badge--danger',
            'yellow' => 'badge--warning',
            'blue'   => 'badge--primary',
            'purple' => 'badge--violet',
            default  => 'badge--neutral',
        };
    }

    #[Computed]
    public function subtitle(): string
    {
        return sprintf(
            '%d دستورکار · %d معوق · %d سررسید امروز',
            $this->openCount,
            $this->overdueCount,
            $this->dueTodayCount
        );
    }


    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.dashboard')
            ->layout('livewire.layouts._dashboard');
    }
}
