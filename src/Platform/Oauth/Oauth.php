<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Platform\Oauth;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class Oauth extends Client
{
    protected $baseUri = 'https://test-mcs.czb365.com';
    protected $urlPrefix = '/services/v3/begin';
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
     * 登录有效期20天
     *
     * @param string $phone
     * @return string
     */
    public function login(string $phone)
    {
        // 20天到期
        return Cache::remember($phone.'-token', 1728000, $this->getToken($phone));
    }

    /**
     * 获取授权码
     *
     * @param string $phone
     * @return string
     */
    public function getSecretCode(string $phone)
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'platformId' => $this->app['config']['platformId'],
            'phone' => $phone,
            'timestamp' => msectime()
        ];
        $response = $this->httpPost($this->urlPrefix.'/getSecretCode', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取token.
     *
     * @return \Closure
     */
    private function getToken(string $phone): \Closure
    {
        return function () use ($phone){
            $query = [
                'app_key' => $this->app['config']['app_key'],
                'platformType' => $this->app['config']['platformId'],
                'platformCode' => $phone,
                'timestamp' => msectime()
            ];
            $response = $this->httpPost($this->urlPrefix.'/platformLoginSimpleAppV4', $query);
            $result = json_decode($response->getBody()->getContents(), true);
            return $result['result']['token'];
        };
    }


}
