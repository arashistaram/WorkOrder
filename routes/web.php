<?php

use App\Http\Middleware\IsLogin;
use App\Livewire\AiChatController;
use App\Livewire\Auth\AuthController;
use App\Livewire\Dashboard\Basic\WorkOrderPriorityManageController;
use App\Livewire\Dashboard\Basic\WorkOrderStatusController;
use App\Livewire\Dashboard\DashboardController;
use App\Livewire\Dashboard\Department\DepartmentController;
use App\Livewire\Dashboard\User\UserManageController;
use App\Livewire\Dashboard\WorkOrder\SubstituteController;
use App\Livewire\Dashboard\WorkOrder\WorkOrderManageController;
use App\Livewire\Dashboard\WorkOrder\WorkOrderDetailController;
use App\Livewire\OutputMessengerManageController;
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

        Route::get(
            '/work-orders/attachments/{attachment}/download',
            function (\App\Models\WorkOrderAttachment $attachment) {
                Gate::authorize('view', $attachment->workOrder);

                $disk = Storage::disk($attachment->disk);

                if (! $disk->exists($attachment->path)) {
                    abort(404, 'فایل یافت نشد.');
                }

                return $disk->download(
                    $attachment->path,
                    $attachment->original_name
                );
            }
        )->name('work-orders.attachments.download');

        Route::get('/work-orders/approvals',
            \App\Livewire\Dashboard\WorkOrder\WorkOrderApprovalController::class
        )->name('work-orders.approvals');

        Route::get('/work-orders/{id}', WorkOrderDetailController::class)
            ->name('work-orders.detail');

        Route::get('/dashboard/substitutes', SubstituteController::class)
            ->name('work-orders.substitutes');

        Route::get('department', DepartmentController::class)
            ->middleware('role:admin')
            ->name("department");

        Route::get('user-manage', UserManageController::class)
            ->middleware('role:admin')
            ->name("user-manage");

        Route::get('work-order-status-manage', WorkOrderStatusController::class)->middleware('role:admin')
            ->name("work-order-status-manage");

        Route::get('work-order-priorities-manage', WorkOrderPriorityManageController::class)
            ->middleware('role:admin')
            ->name('work-order-priorities-manage');

        Route::get('output-messenger-manage', OutputMessengerManageController::class)
            ->name('output-messenger-manage');

        Route::get('/ai-assistant', AiChatController::class)
            ->middleware('role:admin')
            ->name('ai-assistant');
    });

});


Route::get('/ollama-debug-tools', function (\App\Services\Ai\McpAgentService $service) {
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('toolsForOllama');
    $method->setAccessible(true);
    $tools = $method->invoke($service);

    return response()->json([
        'count' => count($tools),
        'tools' => $tools,
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});
