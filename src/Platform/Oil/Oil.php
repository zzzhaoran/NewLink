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

    public function getOilsDetail(array $gasIds, string $phone)
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platformType' => $this->app['config']['platformId'],
            'gasIds' => implode(',', $gasIds),
            'phone' => $phone,
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/gas/queryPriceByPhone', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
