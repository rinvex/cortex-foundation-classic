<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Cortex\Foundation\Support\DfsToken;
use Illuminate\Contracts\Foundation\Application;
use Cortex\Foundation\Exceptions\DfsTokenMismatchException;

class VerifyDfsToken
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected Application $app;

    /**
     * The encrypter implementation.
     *
     * @var \Cortex\Foundation\Support\DfsToken
     */
    protected DfsToken $dfsToken;

    /**
     * The URIs that should be excluded from DFS verification.
     *
     * @var array
     */
    protected array $except = [];

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Cortex\Foundation\Support\DfsToken          $dfsToken
     */
    public function __construct(Application $app, DfsToken $dfsToken)
    {
        $this->app = $app;
        $this->dfsToken = $dfsToken;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Cortex\Foundation\Exceptions\DfsTokenMismatchException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // @TODO: Support DFS on AJAX requests, currently we skip verifying any AJAX requests. To fix this use-case,
        //        we need to dynamically update `_dfs_token` value everytime an AJAX POST request is submitted!
        if (($this->isReading($request) || $this->runningUnitTests() || $this->inExceptArray($request)) && ! $request->ajax()) {
            $this->dfsToken->regenerateToken();
        }

        // @TODO: Support DFS on Livewire requests, currently we skip verifying any requests coming from Livewire. To fix this
        //        use-case, we need to dynamically update `_dfs_token` value everytime a Livewire POST request is submitted!
        if ($request->isMethod('POST') && ! $this->tokensMatch($request) && ! $request->header('X-Livewire')) {
            throw new DfsTokenMismatchException('DFS token mismatch');
        }

        $request->ajax() || $this->dfsToken->regenerateToken();

        return $next($request);
    }

    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function isReading($request): bool
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function runningUnitTests(): bool
    {
        return $this->app->runningInConsole() && $this->app->runningUnitTests();
    }

    /**
     * Determine if the request has a URI that should pass through DFS verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request): bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the session and input DFS tokens match.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function tokensMatch($request): bool
    {
        //dd($this->getTokenFromRequest($request), $this->dfsToken->token());
        return is_string($tokenFromRequest = $this->getTokenFromRequest($request))
               && is_string($dfsToken = $this->dfsToken->token())
               && hash_equals($dfsToken, $tokenFromRequest);
    }

    /**
     * Get the DFS token from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return ?string
     */
    protected function getTokenFromRequest($request): ?string
    {
        return $request->input('_dfs_token') ?: $request->header('X-DFS-TOKEN');
    }
}
