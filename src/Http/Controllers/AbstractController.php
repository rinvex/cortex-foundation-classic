<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

use Illuminate\Support\Str;
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
     * The authentication guard name.
     *
     * @var string
     */
    protected $guard;

    /**
     * The password reset broker name.
     *
     * @var string
     */
    protected $passwordResetBroker;

    /**
     * The email verification broker name.
     *
     * @var string
     */
    protected $emailVerificationBroker;

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
            $accessarea = Str::before(Route::currentRouteName(), '.');
            $passwordResetBroker = $this->getPasswordResetBroker();
            $guard = $this->getGuard();

            $route->setParameter('passwordResetBroker', $passwordResetBroker);
            $route->setParameter('accessarea', $accessarea);
            $route->setParameter('guard', $guard);

            // Activate Guardians
            ! in_array($accessarea, config('cortex.auth.guardians')) || $this->middleware('auth.basic:guardian,username');
        }
    }

    /**
     * Guess guard from accessarea.
     *
     * @return string
     */
    protected function guessGuard(): string
    {
        $accessarea = Str::before(Route::currentRouteName(), '.');
        $guard = mb_strstr($accessarea, 'area', true);

        return config('auth.guards.'.$guard) ? $guard : config('auth.defaults.guard');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return string
     */
    protected function getGuard(): string
    {
        return $this->guard ?? $this->guessGuard();
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

    /**
     * Guess password reset broker from accessarea.
     *
     * @return string
     */
    protected function guessPasswordResetBroker(): string
    {
        $accessarea = Str::before(Route::currentRouteName(), '.');
        $passwordResetBroker = mb_strstr($accessarea, 'area', true);

        return config('auth.passwords.'.$passwordResetBroker) ? $passwordResetBroker : config('auth.defaults.passwords');
    }

    /**
     * Get the password reset broker to be used.
     *
     * @return string
     */
    protected function getPasswordResetBroker(): string
    {
        return $this->passwordResetBroker ?? $this->guessPasswordResetBroker();
    }

    /**
     * Guess email verification broker from accessarea.
     *
     * @return string
     */
    protected function guessEmailVerificationBroker(): string
    {
        $accessarea = Str::before(Route::currentRouteName(), '.');
        $emailVerificationBroker = mb_strstr($accessarea, 'area', true);

        return config('auth.passwords.'.$emailVerificationBroker) ? $emailVerificationBroker : config('auth.defaults.passwords');
    }

    /**
     * Get the email verification broker to be used.
     *
     * @return string
     */
    protected function getEmailVerificationBroker(): string
    {
        return $this->emailVerificationBroker ?? $this->guessEmailVerificationBroker();
    }
}
