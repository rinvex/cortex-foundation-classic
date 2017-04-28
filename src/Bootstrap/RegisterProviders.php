<?php

declare(strict_types=1);

namespace Cortex\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Cortex\Foundation\Providers\AggregateServiceProvider;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
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
