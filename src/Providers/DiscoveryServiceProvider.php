<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
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
     * List of disabled modules.
     *
     * @var array
     */
    protected $disabledModules = [];

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
        // Register modules list
        $modulesManifestPath = $this->app->getCachedModulesPath();
        $modulesManifest = file_exists($modulesManifestPath) ? $this->app['files']->getRequire($modulesManifestPath) : [];
        $this->disabledModules = collect($modulesManifest)->reject(fn ($attributes) => $attributes['active'] && $attributes['autoload'])->keys()->toArray();
        $this->app->singleton('request.modules', fn () => $modulesManifest);

        $this->discoverConfig();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootDiscoveredEvents();
        $this->bootDiscoveredRoutes();

        $this->discoverResources('resources/lang');
        $this->discoverResources('resources/views');
        $this->discoverResources('database/migrations');
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function bootDiscoveredEvents()
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
    public function discoverEvents()
    {
        $eventFiles = $this->app['files']->glob($this->app->path('*/*/src/Listeners'));

        // @TODO: Improve regex, or better filter `glob` results itself!
        $eventFiles = preg_grep('/('.str_replace('/', '\/', implode('|', $this->disabledModules)).')/', $eventFiles, PREG_GREP_INVERT);

        return collect($eventFiles)
            ->reject(function ($directory) {
                return ! is_dir($directory);
            })
            ->reduce(function ($discovered, $directory) {
                return array_merge_recursive(
                    $discovered,
                    DiscoverEvents::within($directory, base_path())
                );
            }, []);
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }

    /**
     * Boot discovered routes for the application.
     *
     * @return void$module
     */
    public function bootDiscoveredRoutes(): void
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
    public function discoverRoutes(string $type): void
    {
        $routeFiles = $this->app['files']->glob($this->app->path("*/*/routes/{$type}/*"));

        // @TODO: Improve regex, or better filter `glob` results itself!
        $routeFiles = preg_grep('/('.str_replace('/', '\/', implode('|', $this->disabledModules)).')/', $routeFiles, PREG_GREP_INVERT);

        collect($routeFiles)
            ->reject(function ($file) {
                return ! is_file($file);
            })
            ->each(function ($file) {
                require $file;
            }, []);
    }

    /**
     * Discover the resources for the application.
     *
     * @param string $type
     *
     * @return void
     */
    public function discoverResources(string $type): void
    {
        $resourceDirs = $this->app['files']->glob($this->app->path("*/*/{$type}"));

        // @TODO: Improve regex, or better filter `glob` results itself!
        $resourceDirs = preg_grep('/('.str_replace('/', '\/', implode('|', $this->disabledModules)).')/', $resourceDirs, PREG_GREP_INVERT);

        collect($resourceDirs)
            ->reject(function ($dir) {
                return ! is_dir($dir);
            })
            ->each(function ($dir) use ($type) {
                $module = str_replace([$this->app->basePath('app/'), "/{$type}"], '', $dir);

                switch ($type) {
                    case 'resources/lang':
                        $this->loadTranslationsFrom($dir, $module);
                        $this->publishesLang($module, true);
                        break;
                    case 'resources/views':
                        $this->loadViewsFrom($dir, $module);
                        $this->publishesViews($module, true);
                        break;
                    case 'database/migrations':
                        $this->autoloadMigrations($module) ?: $this->loadMigrationsFrom($dir);
                        $this->publishesMigrations($module, true);
                        break;
                }
            }, []);
    }

    /**
     * Discover the config for the application.
     *
     * @return void
     */
    public function discoverConfig(): void
    {
        $configFiles = $this->app['files']->glob($this->app->path('*/*/config/config.php'));

        // @TODO: Improve regex, or better filter `glob` results itself!
        $configFiles = preg_grep('/('.str_replace('/', '\/', implode('|', $this->disabledModules)).')/', $configFiles, PREG_GREP_INVERT);

        collect($configFiles)
            ->reject(function ($file) {
                return ! is_file($file);
            })
            ->each(function ($file) {
                $module = str_replace([$this->app->basePath('app/'), '/config/config.php', '/'], ['', '', '.'], $file);

                $this->mergeConfigFrom($file, $module);
                $this->publishesConfig($module, true);
            }, []);
    }
}
