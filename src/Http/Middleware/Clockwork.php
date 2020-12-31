<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;

class Clockwork
{
    /**
     * The Laravel Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new Clockwork middleware instance.
     *
     * @param Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->app['clockwork']->event('Controller')->begin();

        try {
            $response = $next($request);
        } catch (\Exception $e) {
            $this->app[ExceptionHandler::class]->report($e);
            $response = $this->app[ExceptionHandler::class]->render($request, $e);
        }

        return $this->app['clockwork.support']->processRequest($request, $response);
    }

    /**
     * Record the current request after a response is sent.
     *
     * @return void
     */
    public function terminate()
    {
        $this->app['clockwork.support']->recordRequest();
    }
}
