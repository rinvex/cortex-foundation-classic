<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Symfony\Component\Finder\SplFileInfo;

class DiscoverNavigationRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($accessarea = $request->accessarea()) && app('accessareas')->contains('slug', $accessarea)) {
            $moduleResources = app('files')->moduleResources(["routes/menus/{$accessarea}.php", "routes/breadcrumbs/{$accessarea}.php"], 'files', '2');

            collect($moduleResources)
                ->prioritizeLoading()
                ->each(fn (SplFileInfo $file) => require $file->getPathname());
        }

        return $next($request);
    }
}
