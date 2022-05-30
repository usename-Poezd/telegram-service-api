<?php
return [
    'session' => 'telegram.session',

    'settings' => [
        'app_info' => [
            'api_id' => env('TELEGRAM_API_ID'),
            'api_hash' => env('TELEGRAM_API_HASH')
        ]
    ]
];
