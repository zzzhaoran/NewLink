<?php

/*
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

function format_param($config, $param = [])
{
    $query = [
        'app_key' => $config['app_key'],
        'timestamp' => time(),
        'client' => request()->ip(),
        'format' => 'json',
        'v' => '1.0'
    ];
    $sign = '';
    $merge_array = array_merge($query, $param);
    ksort($merge_array);
    foreach ($merge_array as $key => $value) {
        $sign .= "{$key}{$value}";
    }
    $sign = strtoupper(md5($config['app_secret'].$sign.$config['app_secret']));
    $query['sign'] = $sign;
    return $query;
}


