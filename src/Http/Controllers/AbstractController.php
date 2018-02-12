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
        request()->request->add(['guard' => $this->getGuard()]);
        request()->request->add(['accessarea' => str_before(Route::currentRouteName(), '.')]);
    }

    /**
     * Get the broker to be used.
     *
     * @return string
     */
    protected function getBroker(): string
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
