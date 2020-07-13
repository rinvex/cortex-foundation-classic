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

        // unBind {subdomain} route parameter
        $request->route()->forgetParameter('subdomain');

        return $next($request);
    }
}
