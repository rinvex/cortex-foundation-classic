<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class ForgetLocaleRouteParameter
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
        ! config('cortex.foundation.route.locale_prefix') || $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
