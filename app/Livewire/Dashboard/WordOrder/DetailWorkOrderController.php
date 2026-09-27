<?php

namespace App\Livewire\Dashboard\WordOrder;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts._dashboard')]
#[Title('سفارش کار ها')]
final class DetailWorkOrderController extends Component
{
    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.work-order.detail-work-order');
    }
}
