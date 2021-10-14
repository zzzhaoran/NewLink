<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Kernel\Contracts;

use Illuminate\Container\Container;

interface ServiceProviderInterface
{
    public function register(Container $app);
}
