<?php

return [
    'base_url' => env('OUTPUT_MESSENGER_BASE_URL', ''),
    'api_key'  => env('OUTPUT_MESSENGER_API_KEY', ''),
    'sender'   => env('OUTPUT_MESSENGER_SENDER', 'سیستم سفارش کار'),
    'timeout'  => (int) env('OUTPUT_MESSENGER_TIMEOUT', 10),
    'enabled'  => (bool) env('OUTPUT_MESSENGER_ENABLED', true),
];
