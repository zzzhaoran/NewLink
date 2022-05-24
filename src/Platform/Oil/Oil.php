<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Platform\Oil;

use Zhr\NewLink\Kernel\Client;

class Oil extends Client
{
    protected $baseUri = 'https://test-mcs.czb365.com';
    protected $urlPrefix = '/services/v3';
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
     * get Brand
     *
     * @return array
     */
    public function getBrand()
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/gasws/gasBrand', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 油站数据
     *
     * @return void
     */
    public function getOilList()
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'channelId' => $this->app['config']['platformId'],
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/gas/queryGasInfoListOilNoNew', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 油站详情
     *
     * @param array $gasIds
     * @param string $phone
     * @return void
     */
    public function getOilsDetail(array $gasIds, string $phone)
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platformType' => $this->app['config']['platformId'],
            'gasIds' => implode(',', $gasIds),
            'phone' => $phone,
            'requireLabel' => true,
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/gas/queryPriceByPhone', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 附近油站
     *
     * @param array $paginate
     * @param array $position
     * @param string $oilNo
     * @param array $data
     * @return void
     */
    public function nearbyOils(array $paginate, array $position, string $oilNo, array $data = [])
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platformType' => $this->app['config']['platformId'],
            'timestamp' => msectime(),
            'pageIndex' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'userLatStr' => $position['lat'],
            'userLngStr' => $position['lng'],
            'oilNo' => $oilNo,
        ];
        // 手机号
        if(isset($data['phone'])){
            $query['userPhone'] = $data['phone'];
        }
        // 查询品牌ID，多个品牌用英文逗号分隔，默认查询所有品牌
        if(isset($data['brand'])){
            $query['brandTypes'] = $data['brand'];
        }
        // 排序方式。0：按距离，1：按价格。默认按价格排序
        if(isset($data['sort'])){
            $query['sort'] = $data['sort'];
        }
        // 查找范围，具体值见下表，默认查所有
        // 2公里	1
        // 6公里	2
        // 10公里	3
        // 15公里	4
        // 20公里	5
        // 50公里	6
        // 500米	7
        if(isset($data['range'])){
            $query['range'] = $data['range'];
        }
        $response = $this->httpPost($this->urlPrefix.'/gasws/channel/gasListV2', $query);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 支付链接
     *
     * @param string $code
     * @param string $gasId
     * @param string $gunNo
     * @return void
     */
    public function payUrl(string $code, string $gasId, string $gunNo){
        return "https://open.czb365.com/redirection/todo/?platformType={$this->app['config']['platformId']}&authCode={$code}&gasId={$gasId}&gunNo={$gunNo}";
    }
}
