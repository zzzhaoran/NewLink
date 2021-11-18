<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity\User;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class User extends Client
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
     * 获取用户个人中心 URL
     *
     * @param string $phone
     * @return void
     */
    public function getUserCentrerUrl(string $phone)
    {
        $query = [
            'platformCode' => $this->app['config']['platformCode'],
            'seq' => (string)msectime(),
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-electricity_token'),
        ];

        $response = $this->httpPost($this->urlPrefix.'/getUserCenterUrl', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
