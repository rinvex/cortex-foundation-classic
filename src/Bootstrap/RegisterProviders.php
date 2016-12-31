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

namespace Cortex\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Cortex\Foundation\Providers\AggregateServiceProvider;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        // Early load listeners
        $listeners = $app->basePath().'/bootstrap/listeners.php';

        if (file_exists($listeners)) {
            require $listeners;
        }

        // Bootstrap cortex foundation
        $app->register(AggregateServiceProvider::class);
    }
}
