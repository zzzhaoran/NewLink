<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Wikeyun\Recharge;

use Zhr\NewLink\Kernel\Client;

class Recharge extends Client
{
    protected $baseUri = 'https://router.wikeyun.cn';
    protected $urlPrefix = '/rest/Recharge';


    /**
     * 充值话费
     *
     * @param [type] $phone
     * @param [type] $money
     * @param [type] $order_no
     * @param [type] $recharge_type
     * @return void
     */
    public function pushOrder($phone, $money, $order_no, $recharge_type)
    {
        $params = [
            'store_id' => $this->app['config']['store_id'],
            'mobile' => $phone,
            'order_no' => $order_no,
            'money' => $money,
            'recharge_type' => $recharge_type,
            'notify_url' => $this->app['config']['notify_url']
        ];

        $query = format_param($this->app['config'], $params);
        $response = $this->httpPostParams($this->urlPrefix.'/pushOrder', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 订单详情
     *
     * @param [type] $order_no
     * @return void
     */
    public function orderDetail($order_no)
    {
        $params = [
            'order_number' => $order_no,
        ];
        $query = format_param($this->app['config'] ,$params);
        $response = $this->httpPostParams($this->urlPrefix.'/query', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 取消订单
     *
     * @param [type] $order_no
     * @return void
     */
    public function cancel($order_no)
    {
        $params = [
            'order_number' => $order_no,
        ];
        $query = format_param($this->app['config'] ,$params);
        $response = $this->httpPostParams($this->urlPrefix.'/cancel', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 手机号详情
     *
     * @param [type] $order_no
     * @return void
     */
    public function mobileInfo($mobile)
    {
        $params = [
            'mobile' => $mobile,
        ];
        $query = format_param($this->app['config'] ,$params);
        $response = $this->httpPostParams($this->urlPrefix.'/mobileInfo', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 手机号验证
     *
     * @param [type] $order_no
     * @return void
     */
    public function verify($mobile, $money, $recharge_type)
    {
        $params = [
            'mobile' => $mobile,
            'amount' => $money,
            'recharge_type' => $recharge_type,
        ];
        $query = format_param($this->app['config'] ,$params);
        $response = $this->httpPostParams($this->urlPrefix.'/verify', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }
}
