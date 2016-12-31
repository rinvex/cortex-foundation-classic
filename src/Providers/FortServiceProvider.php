<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Cortex\Foundation\Providers;

use Illuminate\Routing\Router;
use Rinvex\Fort\Providers\FortServiceProvider as BaseFortServiceProvider;

class FortServiceProvider extends BaseFortServiceProvider
{
    protected function overrideMiddleware(Router $router)
    {
        // Do NOT override middleware! We have our own custom middleware already!!
    }

    protected function overrideExceptionHandler()
    {
        // Do NOT override exception handler! We have our own custom exception handler already!!
    }
}
