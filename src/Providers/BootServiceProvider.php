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
        $disabledModules = collect($this->app['request.modules'])->reject(fn ($attributes) => $attributes['active'] && $attributes['autoload'])->keys()->toArray();

        // @TODO: Improve regex, or better filter `glob` results itself!
        $bootstrapFiles = $disabledModules ? preg_grep('/('.str_replace('/', '\/', implode('|', $disabledModules)).')/', $bootstrapFiles, PREG_GREP_INVERT) : $bootstrapFiles;

        collect($bootstrapFiles)
            ->reject(function ($file) {
                return ! is_file($file);
            })
            ->each(function ($file) {
                (require $file)();
            }, []);
    }
}
