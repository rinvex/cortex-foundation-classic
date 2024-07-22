<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Cortex\Foundation\Overrides\Illuminate\Session\SessionManager;
use Illuminate\Session\SessionServiceProvider as BaseSessionServiceProvider;

class SessionServiceProvider extends BaseSessionServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton(StartSession::class, function ($app) {
            return new StartSession($app->make(SessionManager::class), function () use ($app) {
                return $app->make(CacheFactory::class);
            });
        });

        // Override `Illuminate\Foundation\Application::registerCoreContainerAliases`, otherwise sessions are screwed!
        $this->app->alias('session', SessionManager::class);
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->app->singleton('session', function ($app) {
            return new SessionManager($app);
        });
    }
}
