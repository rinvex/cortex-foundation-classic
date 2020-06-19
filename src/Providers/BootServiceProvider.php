<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootstrapModules();
    }
    /**
     * Bootstrap application modules.
     *
     * @return void
     */
    public function bootstrapModules(): void
    {
        $bootstrapFiles = $this->app['files']->glob($this->app->path('*/*/bootstrap/module.php'));

        collect($bootstrapFiles)
            ->reject(function ($file) {
                return ! is_file($file);
            })
            ->each(function ($file) {
                (require $file)();
            }, []);
    }
}
