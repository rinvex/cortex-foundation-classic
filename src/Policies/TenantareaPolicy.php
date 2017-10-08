<?php

declare(strict_types=1);

namespace Cortex\Foundation\Policies;

use Rinvex\Fort\Contracts\UserContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantareaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access the tenantarea.
     *
     * @param string                              $ability
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return bool
     */
    public function access($ability, UserContract $user)
    {
        return $user->allAbilities->pluck('slug')->contains($ability);
    }
}
