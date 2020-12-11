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
        $this->mergeConfigFrom($this->app->basePath('/vendor/appstract/laravel-opcache/config/opcache.php'), 'opcache');
    }
}
