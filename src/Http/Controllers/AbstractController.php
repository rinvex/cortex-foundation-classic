<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class AbstractController extends Controller
{
    use DispatchesJobs;
    use ValidatesRequests;
    use AuthorizesRequests;

    /**
     * The authentication guard.
     *
     * @var string
     */
    protected $guard;

    /**
     * The broker name.
     *
     * @var string
     */
    protected $broker;

    /**
     * Whitelisted methods.
     * Array of whitelisted methods which do not need to go through middleware.
     *
     * @var array
     */
    protected $middlewareWhitelist = [];

    /**
     * Create a new abstract controller instance.
     */
    public function __construct()
    {
        // Assign global route parameters
        if ($route = request()->route()) {
            $accessarea = str_before(Route::currentRouteName(), '.');
            $broker = $this->getBroker() ?? $this->guessBroker($accessarea);
            $guard = $this->getGuard() ?? $this->guessGuard($accessarea);

            $route->setParameter('accessarea', $accessarea);
            $route->setParameter('broker', $broker);
            $route->setParameter('guard', $guard);

            // Activate Guardians
            ! in_array($accessarea, config('cortex.auth.guardians')) || $this->middleware('auth.basic:guardians,username');
        }
    }

    /**
     * Guess guard from accessarea.
     *
     * @param string $accessarea
     *
     * @return string|null
     */
    protected function guessGuard(string $accessarea): ?string
    {
        return $this->guard = config('auth.guards.'.$guard = str_plural(mb_strstr($accessarea, 'area', true))) ? $guard : null;
    }

    /**
     * Guess broker from accessarea.
     *
     * @param string $accessarea
     *
     * @return string|null
     */
    protected function guessBroker(string $accessarea): ?string
    {
        return $this->broker = config('auth.passwords.'.$broker = str_plural(mb_strstr($accessarea, 'area', true))) ? $broker : null;
    }

    /**
     * Get the broker to be used.
     *
     * @return string|null
     */
    protected function getBroker(): ?string
    {
        return $this->broker;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return string|null
     */
    protected function getGuard(): ?string
    {
        return $this->guard;
    }

    /**
     * Get the guest middleware for the application.
     */
    protected function getGuestMiddleware()
    {
        return ($guard = $this->getGuard()) ? 'guest:'.$guard : 'guest';
    }

    /**
     * Get the auth middleware for the application.
     *
     * @return string
     */
    protected function getAuthMiddleware(): string
    {
        return ($guard = $this->getGuard()) ? 'auth:'.$guard : 'auth';
    }
}
