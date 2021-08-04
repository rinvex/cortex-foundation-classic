<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        // Register collection loading prioritization macro
        Collection::macro(
            'prioritizeLoading',
            fn () => $this->partition(fn ($item) => Str::contains($item, config('app.provider_loading.priority_5')))->flatMap(fn ($values) => $values)
                 ->partition(fn ($item) => Str::contains($item, config('app.provider_loading.priority_4')))->flatMap(fn ($values) => $values)
                 ->partition(fn ($item) => Str::contains($item, config('app.provider_loading.priority_3')))->flatMap(fn ($values) => $values)
                 ->partition(fn ($item) => Str::contains($item, config('app.provider_loading.priority_2')))->flatMap(fn ($values) => $values)
                 ->partition(fn ($item) => Str::contains($item, config('app.provider_loading.priority_1')))->flatMap(fn ($values) => $values)
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register accessareas into service container, early before booting any module service providers!
        $this->app->singleton('accessareas', fn () => app('cortex.foundation.accessarea')->where('is_active', true)->get());

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
        $enabledModules = collect($this->app['request.modules'])->filter(fn ($attributes) => $attributes['active'] && $attributes['autoload'])->keys()->toArray();

        // @TODO: Improve regex, or better filter `glob` results itself!
        $bootstrapFiles = $enabledModules ? preg_grep('/('.str_replace('/', '\/', implode('|', $enabledModules)).')/', $bootstrapFiles) : $bootstrapFiles;

        collect($bootstrapFiles)
            ->reject(fn ($file) => ! is_file($file))->filter()->prioritizeLoading()
            ->each(fn ($file) => (require $file)());
    }
}
