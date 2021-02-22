<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * The access area name.
     *
     * @var string
     */
    protected $accessarea;

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url(): string
    {
        return parent::url().(config('cortex.foundation.route.trailing_slash') ? '/' : '');
    }

    /**
     * Check if this is an API request.
     *
     * @return boolean
     */
    public function isApi(): bool
    {
        try {
            if ($route = $this->route()) {
                // 1. Route matched through name or uri, and is an API request (ex. /api/users)
                if ($segment = $route->getName()) {
                    $match = Str::before($segment, '.');
                } else {
                    $segment = $route->uri();
                    $match = Str::before($segment, '/');
                }

                if ($match !== 'api') {
                    // 2. Route matched through middleware, and is an API request (ex. /api/users)
                    $match = collect($route->gatherMiddleware())->first(function ($middleware) {
                        return Str::contains($middleware, 'api:');
                    });
                }
            }

            // 3. Catch other use cases:
            // 3.1. Route NOT an API request
            // 3.2. Route NOT matched / Wrong URL (ex. 404 error)
            // 3.3. Route matched but NOT a valid api name (could happen if route is mistakenly named, make sure route names contain valid api prefix)
            return isset($match) && $match === 'api';
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Get access area for current request.
     *
     * @return string
     */
    public function accessarea(): string
    {
        if (! is_null($this->accessarea)) {
            return $this->accessarea;
        }

        try {
            if ($route = $this->route()) {
                // 1. Route matched and is an accessarea request (ex. /adminarea/users)
                if ($segment = $route->getName()) {
                    $area = Str::before($segment, '.');
                } else {
                    $segment = $route->uri();
                    $area = Str::before($segment, '/');
                }

                if (! array_key_exists($area, config('cortex.foundation.route.prefix'))) {
                    // 2. Route matched and is an API request (ex. /api/users)
                    $middleware = collect($route->gatherMiddleware())->first(function ($middleware) {
                        return Str::contains($middleware, 'api:');
                    });

                    if ($middlewareGuard = Str::after($middleware, 'api:')) {
                        $area = $middlewareGuard.'area';
                    }
                }
            }

            // 3. Catch other use cases:
            // 3.1. Route NOT matched / Wrong URL (ex. 404 error)
            // 3.2. Route matched but NOT a valid accessarea (could happen if route is mistakenly named, make sure route names contain valid accessarea prefix)
            return $this->accessarea = isset($area) && array_key_exists($area, config('cortex.foundation.route.prefix')) ? $area : 'frontarea';
        } catch (Throwable $e) {
            // We can't afford any kind of exceptions here, as this is used in the exception handler itself!
            // Imagine if the exception handler, thrown an exception! How, who, and where else to catch?!
            return $this->accessarea = 'frontarea';
        }
    }

    /**
     * Get guard from accessarea.
     *
     * @return string
     */
    public function guard(): string
    {
        $guard = mb_strstr($this->accessarea(), 'area', true);

        return config('auth.guards.'.$guard) ? $guard : config('auth.defaults.guard');
    }

    /**
     * Get password reset broker from accessarea.
     *
     * @return string
     */
    public function passwordResetBroker(): string
    {
        $passwordResetBroker = mb_strstr($this->accessarea(), 'area', true);

        return config('auth.passwords.'.$passwordResetBroker) ? $passwordResetBroker : config('auth.defaults.passwords');
    }

    /**
     * Get email verification broker from accessarea.
     *
     * @return string
     */
    public function emailVerificationBroker(): string
    {
        $emailVerificationBroker = mb_strstr($this->accessarea(), 'area', true);

        return config('auth.passwords.'.$emailVerificationBroker) ? $emailVerificationBroker : config('auth.defaults.passwords');
    }
}
