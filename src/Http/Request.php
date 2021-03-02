<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Illuminate\Support\Str;
use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * The guard name.
     *
     * @var string
     */
    protected $guard;

    /**
     * The access area name.
     *
     * @var string
     */
    protected $accessarea;

    /**
     * Determine if current request is an API.
     *
     * @var bool
     */
    protected $isApi = false;

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
     * @return bool
     */
    public function isApi(): bool
    {
        $this->guard();

        return $this->isApi;
    }

    /**
     * Get access area of current request.
     *
     * @return string
     */
    public function accessarea(): string
    {
        if (! is_null($this->accessarea)) {
            return $this->accessarea;
        }

        $area = $this->guard().'area';

        return $this->isApi ? 'apiarea' : (array_key_exists($area, config('cortex.foundation.route.prefix')) ? $area : (app()->runningInConsole() ? 'consolearea' : 'frontarea'));
    }

    /**
     * Get guard of current request.
     *
     * @return string
     */
    public function guard(): string
    {
        if (! is_null($this->guard)) {
            return $this->guard;
        }

        // A. Route matched
        if ($route = $this->route()) {

            // A.1. Guess guard from: route middleware
            if (($segment = collect($route->middleware())->first(fn ($middleware) => Str::contains($middleware, 'auth:'))) && $guard = Str::after($segment, ':')) {
                ! Str::contains($guard, ['api']) || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    return $guard;
                }
            }

            // A.2. Guess guard from: named route
            if (($segment = $route->getName()) && $guard = Str::before(Str::before($segment, '.'), 'area')) {
                ! Str::contains($guard, ['api']) || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    return $guard;
                }
            }

            // A.3. Guess guard from: prefixed route
            if (($segment = $route->uri()) && $guard = Str::before(Str::before($segment, '/'), 'area')) {
                ! Str::contains($guard, ['api']) || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    return $guard;
                }
            }

            // A.4. Guess guard from: controller namespace
            if (($this->route()->getAction('controller') && $segment = Str::lower(collect(explode('\\', $this->route()->getAction('controller')))->first(fn ($seg) => array_key_exists(Str::lower($seg), config('cortex.foundation.route.prefix'))))) && $guard = Str::before($segment, 'area')) {
                ! Str::contains($guard, ['api']) || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    return $guard;
                }
            }
        }

        // B. Guess guard from: request segments (very early before routes are registered!)
        if (($segment = $this->segment(1)) && $guard = Str::before(Str::before($segment, '/'), 'area')) {
            ! Str::contains($guard, ['api']) || $this->isApi = true;

            if (array_key_exists($guard, config('auth.guards'))) {
                return $guard;
            }
        }

        // C. Catch other use cases:
        // C.1. Route NOT matched / Wrong URL (ex. 404 error)
        // C.2. Route matched but NOT a valid accessarea (could happen if route is mistakenly named, make sure route names contain valid accessarea prefix)
        return $this->isApi ? config('auth.defaults.apiguard') : config('auth.defaults.guard');
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
