<?php

namespace Cortex\Foundation\Overrides\Appstract\Opcache;

use Appstract\Opcache\OpcacheServiceProvider as BaseOpcacheServiceProvider;

class OpcacheServiceProvider extends BaseOpcacheServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // config
        $this->mergeConfigFrom(__DIR__.'/../config/opcache.php', 'opcache');

        // bind routes
        // @TODO: refactor routes
        $this->app->router->group([
            'middleware'    => [\Appstract\Opcache\Http\Middleware\Request::class],
            'prefix'        => config('opcache.prefix'),
            'namespace'     => 'Appstract\Opcache\Http\Controllers',
        ], function ($router) {
            require __DIR__.'/Http/routes.php';
        });
    }
}
