<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Platform\Coupon;

use Zhr\NewLink\Kernel\Client;

class Coupon extends Client
{
    protected $baseUri = 'https://test-mcs.czb365.com';
    protected $urlPrefix = '/services/v3/coupon';
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
     * @param string $phone
     * @return string
     */
    public function userCouponlist(array $paginate, string $phone): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platform' => $this->app['config']['platformId'],
            'mobilePhone' => $phone,
            'pageIndex' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/couponsWithPage', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 兑换卡券
     *
     * @param string $phone
     * @param string $code
     * @return array
     */
    public function convertCoupon(string $phone, string $code): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'mobilePhone' => $phone,
            'code' => $code,
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/couponsWithPage', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 发放卡券
     *
     * @param string $phone
     * @param string $couponCodes
     * @return array
     */
    public function sendCoupon(string $phone, string $couponCodes): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platform' => $this->app['config']['platformId'],
            'mobilePhone' => $phone,
            'couponCodes' => $couponCodes,
            'timestamp' => msectime()
        ];

        $response = $this->httpPost($this->urlPrefix.'/thirdSendCoupon', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
