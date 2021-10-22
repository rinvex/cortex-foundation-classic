<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class UnbindRouteParameters
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
        // unBind {locale} route parameter
        $request->route()->forgetParameter('locale');

        // unBind route domain parameters. Ex: {frontarea}, {adminarea} ..etc
        app('accessareas')->each(function ($accessarea) use ($request) {
            $request->route()->forgetParameter($accessarea->slug);
        });

        return $next($request);
    }
}
