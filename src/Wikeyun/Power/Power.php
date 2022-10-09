<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Wikeyun\Power;

use Zhr\NewLink\Kernel\Client;

class Power extends Client
{
    protected $baseUri = 'https://router.wikeyun.cn';
    protected $urlPrefix = '/rest/Power';

    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * 添加充值卡
     *
     * @param array $data
     * @return void
     */
    public function addCard(array $data)
    {
        $params = [
            'store_id' => $this->app['config']['store_id'],
            'card_num' => $data['card_num'],
            'province' => $data['province'],
            'city' => $data['city'],
            'remark' => $data['remark'] ?? '',
            'type' => $data['type'] ?? 0,
            'user_ext' => $data['user_ext'] ?? ''
        ];
        $query = format_param($this->app['config'], $params);
        $response = $this->httpPostParams($this->urlPrefix.'/addCard', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 充值卡修改
     *
     * @param array $data
     * @param string $card_id
     * @return void
     */
    public function editCard($data,$card_id)
    {
        $params = [
            'card_id' => $card_id,
            'card_num' => $data['card_num'],
            'province' => $data['province'],
            'city' => $data['city'],
            'remark' => $data['remark'] ?? '',
            'type' => $data['type'] ?? 0,
            'user_ext' => $data['user_ext'] ?? ''
        ];
        $query = format_param($this->app['config'], $params);
        $response = $this->httpPostParams($this->urlPrefix.'/editCard', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 充值卡详情
     *
     * @param string $data
     * @return void
     */
    public function cardInfo($card_id)
    {
        $params = [
            'card_id' => $card_id,
        ];
        $query = format_param($this->app['config'], $params);
        $response = $this->httpPostParams($this->urlPrefix.'/cardInfo', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 充值卡删除
     *
     * @param string $data
     * @return void
     */
    public function delCard($card_id)
    {
        $params = [
            'card_id' => $card_id,
        ];
        $query = format_param($this->app['config'], $params);
        $response = $this->httpPostParams($this->urlPrefix.'/delCard', $query, $params);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 充值话费
     *
     * @param [type] $cardId
     * @param [type] $money
     * @param [type] $order_no
     * @param [type] $recharge_type
     * @return void
     */
    public function pushOrder($cardId, $amount, $order_no, $recharge_type)
    {
        $params = [
            'store_id' => $this->app['config']['store_id'],
            'cardId' => $cardId,
            'order_no' => $order_no,
            'amount' => $amount,
            'recharge_type' => $recharge_type,
            'notify_url' => $this->app['config']['notify_url']
        ];

        $query = format_param($this->app['config'] ,$params);
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
}
