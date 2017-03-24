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

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * {@inheritdoc}
     */
    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Cortex\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Cortex\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Fideloper\Proxy\TrustProxies::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $middlewareGroups = [
        'web' => [
            \Cortex\Foundation\Http\Middleware\ForgetLocaleRouteParameter::class,
            \Cortex\Foundation\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Cortex\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Cortex\Foundation\Http\Middleware\NotificationMiddleware::class,
            \Cortex\Foundation\Http\Middleware\Clockwork::class,
            \Rinvex\Fort\Http\Middleware\Abilities::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected $routeMiddleware = [
        'auth' => \Rinvex\Fort\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Rinvex\Fort\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'nohttpcache' => \Rinvex\Fort\Http\Middleware\NoHttpCache::class,
    ];

    /**
     * Temporary store for disabled middleware.
     *
     * @var array
     */
    protected $disabledMiddleware = [];

    /**
     * Temporary store for disabled route middleware.
     *
     * @var array
     */
    protected $disabledRouteMiddleware = [];

    /**
     * Disable middleware.
     *
     * @return void
     */
    public function disableMiddleware()
    {
        $this->disabledMiddleware = $this->middleware;

        $this->middleware = [];
    }

    /**
     * Enable middleware.
     *
     * @return void
     */
    public function enableMiddleware()
    {
        $this->middleware = $this->disabledMiddleware;

        $this->disabledMiddleware = [];
    }

    /**
     * Disable route middleware.
     *
     * @return void
     */
    public function disableRouteMiddleware()
    {
        $this->disabledRouteMiddleware = $this->routeMiddleware;

        $this->routeMiddleware = [];
    }

    /**
     * Enable route middleware.
     *
     * @return void
     */
    public function enableRouteMiddleware()
    {
        $this->routeMiddleware = $this->disabledRouteMiddleware;

        $this->disabledRouteMiddleware = [];
    }
}
