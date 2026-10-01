<?php

use App\Services\OutputMessengerService;
use Illuminate\Support\Facades\Route;

Route::get('output-test', function (OutputMessengerService $svc) {
    return response()->json([
        'all_users'     => $svc->getAllUsers(),
        'online_users'  => $svc->getUsersByStatus('online'),
        'single_user'   => $svc->getUser('a.narimani'),
    ]);
});

//Route::post('output-debug', function (OutputMessengerService $svc) {
//    $ok1 = $svc->notifyUsername(
//        'a.narimani',
//        '📋 سفارش کار جدید',
//        'به شما یک سفارش کار ثبت شده، لطفاً بررسی کنید.'
//    );
//
//    return response()->json([
//        'by_username' => $ok1,
//    ]);
//});

//Route::post('output-debug', function (OutputMessengerService $svc) {
//    $ok1 = $svc->sendNotification(
//        from:    config('output-messenger.sender'),
//        message: "🔔 یک سفارش کار جدید برای تیم ثبت شد.",
//        to:      "سیستم سفارش کار",
//        room:    'فاوا',
//        notify:  1
//    );
//
//    return response()->json([
//        'by_username' => $ok1,
//    ]);
//});


//Route::post('output-debug', function () {
//    $base = rtrim(config('output-messenger.base_url'), '/');
//    $key  = config('output-messenger.api_key');
//
//    $query = http_build_query([
//        'from'    => 'سیستم سفارش کار',
//        'room'    => 'فاوا', // نام گروه فارسی
//        'message' => 'یک سفارش کار جدید برای تیم ثبت شده است.',
//        'color'   => '#C7EDFC',
//        'otr'     => 0,
//        'notify'  => 1,
//    ]);
//
//    $url = $base . '/api/notify?' . $query;
//
//    $res = Http::withHeaders([
//        'API-KEY' => $key,
//        'Accept'  => 'application/json',
//    ])->post($url);
//
//    return response()->json([
//        'url'    => $url,
//        'status' => $res->status(),
//        'body'   => $res->json(),
//    ]);
//});
