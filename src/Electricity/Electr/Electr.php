<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity\Electr;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class Electr extends Client
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
     * 电站列表
     *
     * @param array $paginate
     * @param array $position
     * @param array $data
     * @return void
     */
    public function getElectrList(array $paginate, array $position, array $data = [])
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'location' => "{$position['lng']},{$position['lat']}",
            'pageNo' => $paginate['page'],
            'pageSize' => $paginate['size']
        ];
        // 距离单位km
        if(isset($data['distance'])){
            $query['distance'] = $data['distance'];
        }
        // 充电类型，1：快充；2：慢充
        if(isset($data['chargeType'])){
            $query['chargeType'] = $data['chargeType'];
        }
        // 是否仅显示空闲，1、是 0、否
        if(isset($data['onlyIdle'])){
            $query['onlyIdle'] = $data['onlyIdle'];
        }
        // 标签id
        if(isset($data['tagId'])){
            $query['tagId'] = $data['tagId'];
        }
        $response = $this->httpPost($this->urlPrefix.'/queryStationSummaries', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 电站详情
     *
     * @param array $paginate
     * @param string $position
     * @return void
     */
    public function getElectrDetail(array $position, string $stationId)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'location' => "{$position['lng']},{$position['lat']}",
            'stationId' => $stationId,
        ];

        $response = $this->httpPost($this->urlPrefix.'/queryStationDetail', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 电站列表url
     *
     * @param array $paginate
     * @param string $position
     * @return void
     */
    public function getElectrListUrl(array $position, string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
            'userLatStr' => $position['lat'],
            'userLngStr' => $position['lng'],
        ];

        $response = $this->httpPost($this->urlPrefix.'/getStationListUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 电站详情url
     *
     * @param array $paginate
     * @param string $stationId
     * @param string $position
     * @return void
     */
    public function getElectrDetailUrl(array $position, string $stationId, string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'stationId' => $stationId,
            'token' => Cache::get($phone.'-electricity_token'),
            'userLatStr' => $position['lat'],
            'userLngStr' => $position['lng'],
        ];

        $response = $this->httpPost($this->urlPrefix.'/getStationDetailUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 扫码启动充电
     *
     * @param string $qrcode
     * @param string $phone
     * @return void
     */
    public function scanQrcode(string $qrcode, string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
            'qrCode' => $qrcode,
        ];

        $response = $this->httpPost($this->urlPrefix.'/getStartChargeUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 获取输入枪编码启动充电页面 URL
     *
     * @param string $qrcode
     * @param string $phone
     * @return void
     */
    public function getInputCodeUrl(string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
        ];

        $response = $this->httpPost($this->urlPrefix.'/getInputCodeUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 获取充电信息页面 URL
     *
     * @param string $phone
     * @param string $orderid
     * @return void
     */
    public function getOrderInfoUrl(string $phone, string $orderid)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
            'orderId' => $orderid
        ];

        $response = $this->httpPost($this->urlPrefix.'/getOrderInfoUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 电站标签
     *
     * @return void
     */
    public function getTagsList()
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
        ];
        $response = $this->httpPost($this->urlPrefix.'/queryChargeTags', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

}
