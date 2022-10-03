<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Temporary store for disabled global middleware.
     *
     * @var array
     */
    protected array $disabledGlobalMiddleware = [];

    /**
     * Disable global middleware.
     *
     * @return void
     */
    public function disableGlobalMiddleware(): void
    {
        $this->disabledGlobalMiddleware = $this->middleware;

        $this->middleware = [];
    }

    /**
     * Enable global middleware.
     *
     * @return void
     */
    public function enableGlobalMiddleware(): void
    {
        $this->middleware = $this->disabledGlobalMiddleware;

        $this->disabledGlobalMiddleware = [];
    }
}
