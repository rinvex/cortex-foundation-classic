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
        // Bind {locale} route parameter
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
