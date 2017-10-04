<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class DetectAccessArea
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
        $segment = config('cortex.foundation.route.locale_prefix') ? $request->segment(2) : $request->segment(1);
        $area = config("cortex.foundation.route.prefix.{$segment}");

        ! $area || $request->request->add(['accessarea' => $area]);

        return $next($request);
    }
}
