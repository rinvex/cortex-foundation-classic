<?php

declare(strict_types=1);

if (! function_exists('get_area_roles')) {
    /**
     * Get area roles.
     *
     * @param mixed $currentUser
     *
     * @return string
     */
    function get_area_roles($currentUser)
    {
        $roles = $currentUser->can('superadmin') ? app('cortex.auth.role')->all() : $currentUser->roles;
        $roles = $roles->pluck('title', 'id')->toArray();

        asort($roles);

        return $roles;
    }
}

if (! function_exists('get_area_abilities')) {
    /**
     * Get area abilites.
     *
     * @param mixed $currentUser
     *
     * @return string
     */
    function get_area_abilities($currentUser)
    {
        $abilities = $currentUser->can('superadmin') ? app('cortex.auth.ability')->all() : $currentUser->getAbilities();
        $abilities = $abilities->groupBy('entity_type')->map->pluck('title', 'id')->sortKeys()->toArray();

        return $abilities;
    }
}
