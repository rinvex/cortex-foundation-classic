<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;

class TrailingSlashEnforce
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
        if (! $request->ajax()) {
            $requestUri = $request->getRequestUri();
            $queryString = $request->getQueryString();
            $untrimmedPath = trim($request->getPathInfo(), '/').'/';

            if ($request->method() === 'GET' && mb_strrchr($requestUri, '.') === false && $this->checkQueryString($requestUri, $queryString)) {
                return redirect()->to($untrimmedPath.(! empty($queryString) ? '?'.$queryString : ''), 301);
            }
        }

        return $next($request);
    }

    /**
     * @param $requestUri
     * @param $queryString
     *
     * @return bool
     */
    protected function checkQueryString($requestUri, $queryString)
    {
        return (! $queryString && ! ends_with($requestUri, '/')) || ($queryString && ! ends_with(mb_strstr($requestUri, '?', true), '/'));
    }
}
