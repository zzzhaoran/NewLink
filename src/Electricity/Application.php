<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Electricity;

use Zhr\NewLink\Kernel\ServiceContainer;

class Application extends ServiceContainer
{
    protected $providers = [
        Oauth\ServiceProvider::class,
        Electr\ServiceProvider::class,
        Order\ServiceProvider::class,
        User\ServiceProvider::class,
    ];

    /**
     * Handle dynamic calls.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->base->$method(...$args);
    }
}
