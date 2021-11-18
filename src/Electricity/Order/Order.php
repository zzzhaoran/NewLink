<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity\Order;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class Order extends Client
{
    protected $baseUri = 'https://beta-tch.gokuaidian.com';
    protected $urlPrefix = '/api/v1';
    protected $app_secret = '';

    public function __construct($app)
    {
        parent::__construct($app);
        if(!empty($this->app['config']['baseUri'])){
            $this->baseUri = $this->app['config']['baseUri'];
        }
    }


    /**
     * 获取未完成订单信息
     *
     * @param string $phone
     * @return void
     */
    public function unfinishedOrderUrl(string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
        ];

        $response = $this->httpPost($this->urlPrefix.'/getUnfinishedOrderUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取订单列表
     *
     * @param array $paginate
     * @param array $time
     * @param array $data
     * @return void
     */
    public function getOrderlist(array $paginate,array $time, array $data = [])
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'pageNo' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'startTime' => $time['start_time'],
            'endTime' => $time['end_time'],
        ];
        // 订单 ID
        if(isset($data['orderid'])){
            $query['orderId'] = $data['orderid'];
        }
        // 订单状态，可以传多个值，只能是 2、3、4 的组合，2：停止充电，3：待支付，4：已支付
        if(isset($data['status'])){
            $query['orderStatus'] = $data['status'];
        }

        $response = $this->httpPost($this->urlPrefix.'/queryOrderInfos', $query);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 获取用户订单列表
     *
     * @param array $paginate
     * @param string $time
     * @param int $data
     * @return void
     */
    public function getUserOrderlist(array $paginate, string $phone, int $status = 0)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
            'pageNo' => $paginate['page'],
            'pageSize' => $paginate['size'],

        ];
        if($status != 0){
            $query['orderStatus'] = $status;
        }

        $response = $this->httpPost($this->urlPrefix.'/queryUserOrderInfos', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
