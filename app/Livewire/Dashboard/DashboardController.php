<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('داشبورد')]
final class DashboardController extends Component
{
    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.dashboard')
            ->layout('livewire.layouts._dashboard');
    }
}
