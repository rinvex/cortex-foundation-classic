<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class TrimWww
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
        if (! $request->ajax() && $request->method() === 'GET' && config('cortex.foundation.route.trim_www') && mb_substr($request->header('host'), 0, 4) === 'www.') {
            $request->headers->set('host', mb_substr($request->header('host'), 4));

            return redirect()->to($request->fullUrl(), 301);
        }

        return $next($request);
    }
}
