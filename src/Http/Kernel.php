<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * {@inheritdoc}
     */
    protected $middleware = [
        \Cortex\Foundation\Http\Middleware\TrustProxies::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Cortex\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Cortex\Foundation\Http\Middleware\TrailingSlashEnforce::class,
        \Cortex\Foundation\Http\Middleware\TurbolinksLocation::class,
        \Cortex\Foundation\Http\Middleware\CrawlingRobots::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $middlewareGroups = [
        'web' => [
            \Cortex\Foundation\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            //\Illuminate\Session\Middleware\AuthenticateSession::class,
            \Cortex\Foundation\Http\Middleware\LocalizationRedirect::class,
            \Cortex\Foundation\Http\Middleware\ForgetLocaleRouteParameter::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Cortex\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Cortex\Foundation\Http\Middleware\NotificationMiddleware::class,
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
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'nohttpcache' => \Cortex\Foundation\Http\Middleware\NoHttpCache::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * Forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Cortex\Auth\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
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
    public function disableMiddleware(): void
    {
        $this->disabledMiddleware = $this->middleware;

        $this->middleware = [];
    }

    /**
     * Enable middleware.
     *
     * @return void
     */
    public function enableMiddleware(): void
    {
        $this->middleware = $this->disabledMiddleware;

        $this->disabledMiddleware = [];
    }

    /**
     * Disable route middleware.
     *
     * @return void
     */
    public function disableRouteMiddleware(): void
    {
        $this->disabledRouteMiddleware = $this->routeMiddleware;

        $this->routeMiddleware = [];
    }

    /**
     * Enable route middleware.
     *
     * @return void
     */
    public function enableRouteMiddleware(): void
    {
        $this->routeMiddleware = $this->disabledRouteMiddleware;

        $this->disabledRouteMiddleware = [];
    }
}
