<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class DiscoverNavigationRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $accessarea = app('request.accessarea');

        $menuFiles = app('files')->glob(app()->path("*/*/routes/menus/{$accessarea}.php"));
        $breadcrumbFiles = app('files')->glob(app()->path("*/*/routes/breadcrumbs/{$accessarea}.php"));

        // @TODO: Improve this regex, or even better filter `glob` results itself!
        $disabledModules = collect(app('request.modules'))->reject(fn ($attributes) => $attributes['active'] && $attributes['autoload'])->keys()->toArray();
        $menuFiles = $disabledModules ? preg_grep('/('.str_replace('/', '\/', implode('|', $disabledModules)).')/', $menuFiles, PREG_GREP_INVERT) : $menuFiles;
        $breadcrumbFiles = $disabledModules ? preg_grep('/('.str_replace('/', '\/', implode('|', $disabledModules)).')/', $breadcrumbFiles, PREG_GREP_INVERT) : $breadcrumbFiles;

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
