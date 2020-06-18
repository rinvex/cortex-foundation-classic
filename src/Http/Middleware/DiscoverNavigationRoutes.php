<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class DiscoverNavigationRoutes
{
    /**
     * Handle an incoming request.
     *
     * @TODO: Check for enabled modules only!
     *      We should have the ability to disable modules without uninstalling!!
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $accessarea = $request->route('accessarea');

        $menuFiles = app('files')->glob(app()->path("*/*/routes/menus/{$accessarea}.php"));
        $breadcrumbFiles = app('files')->glob(app()->path("*/*/routes/breadcrumbs/{$accessarea}.php"));

        collect($menuFiles)->merge($breadcrumbFiles)
                           ->reject(function ($file) {
                               return ! is_file($file);
                           })
                           ->each(function ($file) {
                               require $file;
                           }, []);

        return $next($request);
    }
}
