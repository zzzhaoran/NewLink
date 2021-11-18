<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Wikeyun\User;

use Zhr\NewLink\Kernel\Client;

class User extends Client
{
    protected $baseUri = 'https://router.wikeyun.cn';
    protected $urlPrefix = '/rest/User';

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function getbalance()
    {
        $query = format_param($this->app['config']);
        $response = $this->httpPostParams($this->urlPrefix.'/query', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}
