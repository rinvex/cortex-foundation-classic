<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
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

        try {
            // Just check if we have DB connection! This is to avoid
            // exceptions on new projects before configuring database options
            DB::connection()->getPdo();

            if (Schema::hasTable(config('cortex.foundation.tables.accessareas'))) {
                $this->callAfterResolving('router', fn () => $this->bootDiscoveredRoutes());
            }
        } catch (Exception $e) {
            // Be quiet! Do not do or say anything!!
        }
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
        return collect(['module', 'extension'])->flatMap(function ($moduleType) {
            return collect($this->app['files']->{"{$moduleType}Resources"}('src/Listeners', 'directories'))->prioritizeLoading()->reduce(fn($discovered, SplFileInfo $dir) => array_merge_recursive($discovered, DiscoverEvents::within($dir->getPathname(), base_path())), []);
        })->all();
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

        // Discover broadcasting channels
        $this->discoverBroadcasts();

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
     * Discover the broadcast channels for the application.
     *
     * @return void
     */
    protected function discoverBroadcasts(): void
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}('routes/broadcasts/channels.php', 'files', 2);

            collect($resources)
                ->prioritizeLoading()
                ->each(fn (SplFileInfo $file) => require $file->getPathname());
        }
    }

    /**
     * Discover the routes for the application.
     *
     * @param string $resourceType
     *
     * @return void
     */
    protected function discoverRoutes(string $resourceType): void
    {
        $accessareas = app('accessareas')->map(fn ($accessarea) => "routes/$resourceType/$accessarea->slug")->toArray();

        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}($accessareas, 'files', 2);

            collect($resources)
                ->prioritizeLoading()
                ->each(fn (SplFileInfo $file) => require $file->getPathname());
        }
    }

    /**
     * Discover the resources for the application.
     *
     * @param string $resourceType
     *
     * @return void
     */
    protected function discoverResources(string $resourceType): void
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}($resourceType, 'directories');
            $configPath = config("rinvex.composer.cortex-{$moduleType}.path");

            collect($resources)
                ->prioritizeLoading()
                ->each(function (SplFileInfo $dir) use ($resourceType, $moduleType, $configPath) {
                    $packageName = str_replace([$configPath . '/', "/$resourceType"], '', $dir->getPathname());
                    $moduleName = app('cortex.foundation.extensions.enabled')[$packageName]['extends'] ?? $packageName;

                    switch ($resourceType) {
                        case 'resources/lang':
                            $this->loadTranslationsFrom($dir->getPathname(), $moduleName);
                            $this->publishTranslationsFrom($dir->getPathname(), $moduleName);
                            break;
                        case 'resources/views':
                            $this->loadViewsFrom($dir->getPathname(), $moduleName);
                            $this->publishViewsFrom($dir->getPathname(), $moduleName);
                            break;
                        case 'database/migrations':
                            ! $this->app['config'][str_replace('/', '.', $moduleName).'.autoload_migrations'] || $this->loadMigrationsFrom($dir->getPathname());
                            $this->publishMigrationsFrom($dir->getPathname(), $moduleName);
                            break;
                    }
                });
        }
    }


    /**
     * Discover the config for the application.
     *
     * @return void
     */
    protected function discoverConfig(): void
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}('config/config.php');
            $configPath = config("rinvex.composer.cortex-{$moduleType}.path");

            collect($resources)
                ->prioritizeLoading()
                ->each(function (SplFileInfo $file) use ($configPath) {
                    $moduleName = str_replace([$configPath . '/', '/config/config.php'], '', $file->getPathname());
                    $this->mergeConfigFrom($file->getPathname(), str_replace('/', '.', $moduleName));
                    $this->publishConfigFrom($file->getPathname(), $moduleName);
                });
        }
    }
}
