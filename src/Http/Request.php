<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Illuminate\Support\Arr;
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
     * Determine if the current request probably expects a JSON response.
     *
     * @return bool
     */
    public function expectsJson()
    {
        return ($this->ajax() && ! $this->pjax() && $this->acceptsAnyContentType()) || $this->wantsJson() || $this->isApi();
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

        $accessarea = (app()->has('request.tenant') && app('request.tenant') && $this->guard() === 'member' ? 'tenant' : $this->guard()).'area';

        return $this->accessarea = $this->isApi ? 'apiarea' : (app()->has('accessareas') && app('accessareas')->contains('slug', $accessarea) ? $accessarea : (app()->runningInConsole() ? 'consolearea' : 'frontarea'));
    }

    /**
     * Get the guard of current the request.
     *
     * REQUEST PIPELINE
     * ------------------
     *  - Register all Service Providers
     *      - Packages: Boot service providers
     *      - Modules: BootServiceProvider
     *          - Boostrap modules
     *      - DiscoveryServiceProvider
     *          - Register Routes
     *      - Module Boot all other service providers
     *  - Execute Global Middleware
     * --------------------------------------------------------------------------- >> CAN BE CALLED AFTER THIS POINT!
     *  - Execute controller constructors / request()->route() first availability
     *  - Execute Route Middleware
     *      - Execute tenantable middleware!
     *      - Execute menus & breadcrumbs middleware
     *
     * NOTES
     * ------
     * - Route names, and controller namespaces must match guards.
     *   Ex: 'adminarea.cortex.auth.abilities.index' => means guard is 'Admin' for that named route!
     *       'Cortex\Auth\Http\Controllers\Adminarea\AbilitiesController' => means guard is 'Admin' for that controller!
     *
     * - Route middleware list is not complete, since the controller constructors can still append the list in runtime!
     *
     * - URL prefix itself does NOT necessarily match the guard name, however we query the accessarea details
     *   using its URL prefix. Ex: Accessarea 'adminarea' could have URL prefix of '/blahblah' or '/secret'
     *
     * - Accessarea slug must match guard name. Ex: 'adminarea' always use 'admin' guard, and so on.
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
            // A.1. Guess guard from: named route
            if (($segment = $route->getName()) && $guard = Str::before($accessarea = Str::lower(Str::before($segment, '.')), 'area')) {
                ! Str::startsWith($guard, 'api') || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    $this->accessarea = $accessarea;

                    return $this->guard = $guard;
                }
            }

            // A.2. Guess guard from: controller namespace
            if (($this->route()->getAction('controller') && $accessarea = Str::lower(collect(explode('\\', $this->route()->getAction('controller')))->first(fn ($seg) => app()->has('accessareas') && app('accessareas')->contains('slug', Str::lower($seg))))) && $guard = Str::before($accessarea, 'area')) {
                ! Str::startsWith($guard, 'api') || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    $this->accessarea = $accessarea;

                    return $this->guard = $guard;
                }
            }

            // A.3. Guess guard from: route middleware
            if (($segment = collect($route->middleware())->first(fn ($middleware) => Str::contains($middleware, 'auth:'))) && $guard = Str::after($segment, ':')) {
                ! Str::startsWith($guard, 'api') || $this->isApi = true;

                if (array_key_exists($guard, config('auth.guards'))) {
                    return $this->guard = $guard;
                }
            }
        }

        // B. Guess guard from: accessarea-specific prefixed url (possibly route not found / 404 page)
        if (($rawSegment = $this->segment(1)) && app()->has('accessareas') && ($segment = app('accessareas')->first(fn ($accessarea) => $accessarea['prefix'] === $rawSegment)?->slug) && $guard = Str::before($segment, 'area')) {
            ! Str::startsWith($guard, 'api') || $this->isApi = true;

            if (array_key_exists($guard, config('auth.guards'))) {
                return $this->guard = $guard;
            }
        }

        // C. Guess guard from: accessarea-specific domain
        if (! empty($domains = Arr::first(config('app.domains'), fn ($accessareas, $domain) => $domain === $this->getHost())) && ($segment = $domains[0]) && $guard = Str::before($segment, 'area')) {
            ! Str::startsWith($guard, 'api') || $this->isApi = true;

            if (array_key_exists($guard, config('auth.guards'))) {
                return $this->guard = $guard;
            }
        }

        // D. Catch other use cases:
        // D.1. Route NOT matched / Wrong URL (ex. 404 error)
        // D.2. Route matched but NOT a valid accessarea. This could happen if route is mistakenly named,
        //      or controller namespace is not correct, make sure route names contain valid accessarea prefix.
        // D.3. Route matched, but guessed guard is not found. Ex: 'tenant', so the guard is defaulted to 'member'.
        return $this->guard = $this->isApi ? config('auth.defaults.apiguard') : config('auth.defaults.guard');
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
