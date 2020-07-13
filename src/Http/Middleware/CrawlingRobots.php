<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class CrawlingRobots
{
    protected $response;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);
        $shouldIndex = $this->shouldIndex($request);

        if (is_bool($shouldIndex)) {
            return $this->responseWithRobots($shouldIndex ? 'all' : 'none');
        }

        if (is_string($shouldIndex)) {
            return $this->responseWithRobots($shouldIndex);
        }

        throw new Exception(trans('cortex/foundation::messages.invalid_indexing_rule'));
    }

    /**
     * Response with robots.
     *
     * @param string $contents
     *
     * @return mixed
     */
    protected function responseWithRobots(string $contents)
    {
        $this->response->headers->set('x-robots-tag', $contents, false);

        return $this->response;
    }

    /**
     * @return string|bool
     */
    protected function shouldIndex(Request $request)
    {
        return app()->environment('production') && in_array(app('request.accessarea'), config('cortex.foundation.indexable'));
    }
}
