<?php
return [
    // 开放平台
    'platform' => [
        'app_key'       => env('NEWLINK_PLATFORM_KEY', ''),
        'app_secret'    => env('NEWLINK_PLATFORM_SECRET', ''),
        'platformId'    => env('NEWLINK_PLATFORM_PLATFORM_ID', ''),
        'baseUri'       => ''
    ],

    // 快电
    'electricity' => [
        'platformCode'  => env('NEWLINK_ELECTRICITY_PLATFORM_ID', ''),
        'private_key'   => env('NEWLINK_ELECTRICITY_PRIVATE_KEY', ''),
        'baseUri'       => ''
    ],

    // 微客云
    'wikeyun' => [
        'app_key'       => env('WikEYUN_PLATFORM_KEY', ''),
        'app_secret'    => env('WikEYUN_PLATFORM_SECRET', ''),
        'store_id'      => '',
        'notify_url'    => ''
    ],

    // 超时时间
    'http' => [
        'timeout' => 60.0,
    ]
];
