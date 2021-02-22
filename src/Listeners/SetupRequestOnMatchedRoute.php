<?php

declare(strict_types=1);

namespace Cortex\Foundation\Listeners;

use Silber\Bouncer\Database\Models;
use Illuminate\Routing\Events\RouteMatched;

class SetupRequestOnMatchedRoute
{
    /**
     * The access area name.
     *
     * @var string
     */
    protected $accessarea;

    /**
     * Setup request params on RouteMatched event.
     *
     * @param \Illuminate\Auth\Events\Lockout $event
     *
     * @return void
     */
    public function handle(RouteMatched $event): void
    {
        // Assign global route parameters
        if ($route = request()->route()) {
            $this->accessarea = request()->getAccessArea();
            $passwordResetBroker = $this->getPasswordResetBroker();
            $emailVerificationBroker = $this->getEmailVerificationBroker();
            $guard = $this->getGuard();
            Models::setUsersModel(config("cortex.auth.models.{$guard}"));
        }

        app()->singleton('request.emailVerificationBroker', fn () => $emailVerificationBroker ?? null);
        app()->singleton('request.passwordResetBroker', fn () => $passwordResetBroker ?? null);
        app()->singleton('request.user', fn () => auth()->guard($guard ?? null)->user());
        app()->singleton('request.accessarea', fn () => $this->accessarea ?? null);
        app()->singleton('request.guard', fn () => $guard ?? null);
    }

    /**
     * Get guard from accessarea.
     *
     * @return string
     */
    protected function getGuard(): string
    {
        $guard = mb_strstr($this->accessarea, 'area', true);

        return config('auth.guards.'.$guard) ? $guard : config('auth.defaults.guard');
    }

    /**
     * Get password reset broker from accessarea.
     *
     * @return string
     */
    protected function getPasswordResetBroker(): string
    {
        $passwordResetBroker = mb_strstr($this->accessarea, 'area', true);

        return config('auth.passwords.'.$passwordResetBroker) ? $passwordResetBroker : config('auth.defaults.passwords');
    }

    /**
     * Get email verification broker from accessarea.
     *
     * @return string
     */
    protected function getEmailVerificationBroker(): string
    {
        $emailVerificationBroker = mb_strstr($this->accessarea, 'area', true);

        return config('auth.passwords.'.$emailVerificationBroker) ? $emailVerificationBroker : config('auth.defaults.passwords');
    }
}
