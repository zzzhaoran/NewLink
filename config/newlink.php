<?php
return [
    // 开放平台
    'platform' => [
        'app_key'       => env('NEWLINK_PLATFORM_KEY', ''),
        'app_secret'    => env('NEWLINK_PLATFORM_SECRET', ''),
        'platformId'    => env('NEWLINK_PLATFORM_PLATFORM_ID', ''),
        'baseUri'       => ''
    ],

    // 超时时间
    'http' => [
        'timeout' => 60.0,
    ]
];
