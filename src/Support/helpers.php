<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

if (! function_exists('backend_uri')) {
    /**
     * Return the backend uri.
     *
     * @return string
     */
    function backend_uri()
    {
        return config('rinvex.cortex.backend.uri');
    }
}

if (! function_exists('backend_path')) {
    /**
     * Generate an absolute URL to the given admin path.
     *
     * @param string $path
     * @param array  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function backend_path($path, array $parameters = [], $secure = null)
    {
        return url(backend_uri().'/'.ltrim($path), $parameters, $secure);
    }
}

if (! function_exists('is_backend')) {
    /**
     * Check if the current request is an admin request or not.
     *
     * @return bool
     */
    function is_backend()
    {
        return starts_with(request()->path(), backend_uri());
    }
}
