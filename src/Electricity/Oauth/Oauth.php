<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity\Oauth;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class Oauth extends Client
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
     * 登录有效期14天
     *
     * @param string $phone
     * @return string
     */
    public function login(string $phone)
    {
        // 14天到期
        Cache::forget($phone.'-electricity_token');
        return Cache::remember($phone.'-electricity_token', 1209600, $this->getToken($phone));
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
                'platformCode' => $this->app['config']['platformCode'],
                'phone' => $phone,
                'seq' => (string)msectime(),
                'timestamp' => msectime()
            ];
            $response = $this->httpPost($this->urlPrefix.'/queryUserToken', $query);
            $result = json_decode($response->getBody()->getContents(), true);
            if($result['ret'] != 0){
                throw new \Exception($result['msg']);
            }
            return $result['data']['token'];
        };
    }
}
