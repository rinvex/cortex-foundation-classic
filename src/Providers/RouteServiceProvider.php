<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\CachesRoutes;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $dispatcher): void
    {
        // Discover web routes
        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            $this->discoverRoutes('web');
        }

        // Discover broadcasting channel routes
        $this->discoverRoutes('channels');

        $this->app->booted(function () {
            if ($this->app->routesAreCached()) {
                require $this->app->getCachedRoutesPath();
            } else {
                $this->app['router']->getRoutes()->refreshNameLookups();
                $this->app['router']->getRoutes()->refreshActionLookups();
            }
        });
    }

    /**
     * Discover the web routes for the application.
     *
     * @param string $type
     *
     * @return void
     */
    public function discoverRoutes(string $type): void
    {
        collect($this->discoverRoutesWithin($type))
            ->reject(function ($file) {
                return ! is_file($file);
            })
            ->each(function ($file) {
                require $file;
            }, []);
    }

    /**
     * Get the route files that should be used to register web routes.
     *
     * @param string $type
     *
     * @return array
     */
    protected function discoverRoutesWithin(string $type): array
    {
        return $this->app['files']->glob($this->app->path("*/*/routes/{$type}/*"));
    }
}
