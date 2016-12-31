<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Cortex\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Cortex\Foundation\Http\Middleware\TrailingSlashEnforce;
use Cortex\Foundation\Overrides\Illuminate\Routing\Redirector;
use Cortex\Foundation\Overrides\Illuminate\Routing\UrlGenerator;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Cortex\Foundation\Overrides\Mcamara\LaravelLocalization\LaravelLocalization;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Early set application locale globaly
        $this->app['laravellocalization']->setLocale();

        // Load routes
        $this->loadRoutes($router);

        // Require Support Files
        $this->requireSupportFiles();

        // Register a view file namespace
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/foundation');

        // Register a translation file namespace
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/foundation');
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->overrideLaravelLocalization();
        $this->overrideUrlGenerator();
        $this->overrideRedirector();
        $this->registerGeneric();
        $this->setBackendUri();

        // Add required middleware to the stack
        $this->prependMiddleware();
    }

    /**
     * Prepends the bootstrap middleware.
     *
     * @return void
     */
    protected function prependMiddleware()
    {
        if ($this->app['config']->get('rinvex.cortex.route.locale_prefix') && $this->app['config']->get('rinvex.cortex.route.locale_redirect')) {
            $this->app[Kernel::class]->prependMiddleware(LaravelLocalizationRedirectFilter::class);
        }

        if ($this->app['config']->get('rinvex.cortex.route.trailing_slash')) {
            $this->app[Kernel::class]->prependMiddleware(TrailingSlashEnforce::class);
        }
    }

    /**
     * Require support files.
     *
     * @return void
     */
    protected function requireSupportFiles()
    {
        // Load the functions
        $helpers = $this->app->path().'/Support/helpers.php';

        if ($this->app['files']->exists($helpers)) {
            require $helpers;
        }

        // Load the form macros
        $macros = $this->app->path().'/Support/macros.php';

        if ($this->app['files']->exists($macros)) {
            require $macros;
        }
    }

    /**
     * Registers the Generic bindings.
     *
     * @return void
     */
    protected function registerGeneric()
    {
        if (! $this->app->environment('production')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Override the Redirector instance.
     *
     * @return void
     */
    protected function overrideRedirector()
    {
        $this->app['redirect'] = $this->app->share(function ($app) {
            $redirector = new Redirector($app['url']);

            // If the session is set on the application instance, we'll inject it into
            // the redirector instance. This allows the redirect responses to allow
            // for the quite convenient "with" methods that flash to the session.
            if (isset($app['session.store'])) {
                $redirector->setSession($app['session.store']);
            }

            return $redirector;
        });
    }

    /**
     * Override the UrlGenerator instance.
     *
     * @return void
     */
    protected function overrideUrlGenerator()
    {
        $this->app['url'] = $this->app->share(function ($app) {
            $routes = $app['router']->getRoutes();

            // The URL generator needs the route collection that exists on the router.
            // Keep in mind this is an object, so we're passing by references here
            // and all the registered routes will be available to the generator.
            $app->instance('routes', $routes);

            $url = new UrlGenerator(
                $routes, $app->rebinding(
                    'request', $this->requestRebinder()
                )
            );

            $url->setSessionResolver(function () {
                return $this->app['session'];
            });

            // If the route collection is "rebound", for example, when the routes stay
            // cached for the application, we will need to rebind the routes on the
            // URL generator instance so it has the latest version of the routes.
            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });

            return $url;
        });
    }

    /**
     * Get the URL generator request rebinder.
     *
     * @return \Closure
     */
    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }

    /**
     * Override the LaravelLocalization instance.
     *
     * @return void
     */
    protected function overrideLaravelLocalization()
    {
        $this->app['laravellocalization'] = $this->app->share(function () {
            return new LaravelLocalization();
        });
    }

    /**
     * Set the backend uri on the url generator.
     *
     * @return void
     */
    protected function setBackendUri()
    {
        $this->app['url']->setBackendUri(backend_uri());
    }

    /**
     * Load the module routes.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function loadRoutes(Router $router)
    {
        // Load routes
        if ($this->app->routesAreCached()) {
            $this->app->booted(function () {
                require $this->app->getCachedRoutesPath();
            });
        } else {
            // Load the application routes
            $router->group([
                'prefix' => $this->app['config']['rinvex.cortex.route.locale_prefix'] ? '{locale}' : '',
                'namespace' => 'Cortex\Foundation\Http\Controllers',
                'middleware' => 'web',
            ], function ($router) {
                require __DIR__.'/../../routes/web.php';
            });

            $this->app->booted(function () use ($router) {
                $router->getRoutes()->refreshNameLookups();
            });
        }
    }
}
