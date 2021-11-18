<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity\Oauth;

use Zhr\NewLink\Kernel\Contracts\ServiceProviderInterface;
use Illuminate\Container\Container;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['oauth'] = function ($app) {
            return new Oauth($app);
        };
    }
}
