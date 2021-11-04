<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Platform\Order;

use Zhr\NewLink\Kernel\Client;

class Order extends Client
{
    protected $baseUri = 'https://test-mcs.czb365.com';
    protected $urlPrefix = '/services/v3/orderws';
    protected $app_secret = '';

    public function __construct($app)
    {
        parent::__construct($app);
        $this->app_secret = $this->app['config']['app_secret'];
        if(!empty($this->app['config']['baseUri'])){
            $this->baseUri = $this->app['config']['baseUri'];
        }
    }

    /**
     * 订单详情
     *
     * @param array $paginate
     * @param array $data
     * @return array
     */
    public function orderInfo(array $paginate, array $data = []): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'orderSource' => $this->app['config']['platformId'],
            'pageIndex' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'timestamp' => msectime()
        ];
        // 订单时间 时间格式 2017-09-27 00:00:00
        if(isset($data['begin_time'])){
            $query['beginTime'] = date('Y-m-d H:i:s', $data['begin_time']);
            $query['endTime'] = date('Y-m-d H:i:s', $data['end_time']);
        }
        // 订单状态  (1:已支付;4:退款申请中;5:已退款;6:退款失败;)
        if(isset($data['status'])){
            $query['orderStatus'] = $data['status'];
        }
        // 订单号
        if(isset($data['orderid'])){
            $query['orderId'] = $data['orderid'];
        }
        // 手机号
        if(isset($data['phone'])){
            $query['phone'] = $data['phone'];
        }
        $response = $this->httpPost($this->urlPrefix.'/platformOrderInfoV2', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
