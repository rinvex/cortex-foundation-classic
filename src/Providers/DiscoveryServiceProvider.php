<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Cortex\Foundation\Overrides\Illuminate\Foundation\Events\DiscoverEvents;

class DiscoveryServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

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
        $this->discoverConfig();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->callAfterResolving('migrator', fn () => $this->discoverResources('database/migrations'));
        $this->callAfterResolving('translator', fn () => $this->discoverResources('resources/lang'));
        $this->callAfterResolving('view', fn () => $this->discoverResources('resources/views'));
        $this->callAfterResolving('events', fn () => $this->bootDiscoveredEvents());
        $this->callAfterResolving('router', fn () => $this->bootDiscoveredRoutes());
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    protected function bootDiscoveredEvents()
    {
        if ($this->app->eventsAreCached()) {
            $cache = require $this->app->getCachedEventsPath();

            $events = $cache[get_class($this)] ?? [];
        } else {
            $events = array_merge_recursive(
                $this->discoverEvents(),
                $this->listens()
            );
        }

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                Event::listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }

    /**
     * Discover the events and listeners for the application.
     *
     * @return array
     */
    protected function discoverEvents()
    {
        $moduleResources = $this->app['files']->moduleResources('src/Listeners', 'directories');

        return collect($moduleResources)
            ->prioritizeLoading()
            ->reduce(fn ($discovered, SplFileInfo $dir) => array_merge_recursive($discovered, DiscoverEvents::within($dir->getPathname(), base_path())), []);
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    protected function listens()
    {
        return $this->listen;
    }

    /**
     * Boot discovered routes for the application.
     *
     * @return void$module
     */
    protected function bootDiscoveredRoutes(): void
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
     * Discover the routes for the application.
     *
     * @param string $type
     *
     * @return void
     */
    protected function discoverRoutes(string $type): void
    {
        $accessareaResources = app('accessareas')->map(fn ($accessarea) => "routes/{$type}/{$accessarea->slug}")->toArray();
        $moduleResources = $accessareaResources ? $this->app['files']->moduleResources($accessareaResources, 'files', 2) : [];

        collect($moduleResources)
            ->prioritizeLoading()
            ->each(fn (SplFileInfo $file) => require $file->getPathname());
    }

    /**
     * Discover the resources for the application.
     *
     * @param string $type
     *
     * @return void
     */
    protected function discoverResources(string $type): void
    {
        $moduleResources = $this->app['files']->moduleResources($type, 'directories');

        collect($moduleResources)
            ->prioritizeLoading()
            ->each(function (SplFileInfo $dir) use ($type) {
                $module = str_replace([$this->app->path().DIRECTORY_SEPARATOR, "/{$type}"], '', $dir->getPathname());

                switch ($type) {
                    case 'resources/lang':
                        $this->loadTranslationsFrom($dir->getPathname(), $module);
                        $this->publishesLang($module, true);
                        break;
                    case 'resources/views':
                        $this->loadViewsFrom($dir->getPathname(), $module);
                        $this->publishesViews($module, true);
                        break;
                    case 'database/migrations':
                        ! $this->autoloadMigrations($module) || $this->loadMigrationsFrom($dir->getPathname());
                        $this->publishesMigrations($module, true);
                        break;
                }
            });
    }

    /**
     * Discover the config for the application.
     *
     * @return void
     */
    protected function discoverConfig(): void
    {
        $moduleResources = $this->app['files']->moduleResources('config/config.php');

        collect($moduleResources)
            ->prioritizeLoading()
            ->each(function (SplFileInfo $file) {
                $module = str_replace([$this->app->path().DIRECTORY_SEPARATOR, '/config/config.php'], '', $file->getPathname());

                $this->mergeConfigFrom($file->getPathname(), str_replace('/', '.', $module));

                $this->publishesConfig($module, true);
            });
    }
}
