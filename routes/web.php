<?php

use App\Http\Middleware\IsLogin;
use App\Livewire\Auth\AuthController;
use App\Livewire\Dashboard\DashboardController;
use App\Livewire\Dashboard\WordOrder\DetailWorkOrderController;
use App\Livewire\Dashboard\WordOrder\WorkOrderController;
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
        Route::get('work-orders', WorkOrderController::class)->name("work-orders");
        Route::get('detail-work-orders', DetailWorkOrderController::class)->name("detail-work-orders");
    });

});
