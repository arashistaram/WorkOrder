<?php

use App\Http\Middleware\IsLogin;
use App\Livewire\Auth\AuthController;
use App\Livewire\Dashboard\Basic\WorkOrderPriorityManageController;
use App\Livewire\Dashboard\Basic\WorkOrderStatusController;
use App\Livewire\Dashboard\DashboardController;
use App\Livewire\Dashboard\Department\DepartmentController;
use App\Livewire\Dashboard\User\UserManageController;
use App\Livewire\Dashboard\WorkOrder\WorkOrderManageController;
use App\Livewire\Dashboard\WorkOrder\WorkOrderDetailController;
use Illuminate\Support\Facades\Route;

Route::get("/", AuthController::class)
    ->name("auth");

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('auth');
})->middleware('auth')->name('logout');

Route::middleware(IsLogin::class)->group(function () {

    Route::prefix("dashboard")->group(function () {
        Route::get("/", DashboardController::class)->name("dashboard");

        Route::get('/work-orders', WorkOrderManageController::class)
            ->name('work-orders');

        Route::get('/work-orders/{id}', WorkOrderDetailController::class)
            ->name('work-orders.detail');

        Route::get('department', DepartmentController::class)
            ->name("department");

        Route::get('user-manage', UserManageController::class)
            ->name("user-manage");

        Route::get('work-order-status-manage', WorkOrderStatusController::class)
            ->name("work-order-status-manage");

        Route::get('work-order-priorities-manage', WorkOrderPriorityManageController::class)
            ->name('work-order-priorities-manage');

    });

});
