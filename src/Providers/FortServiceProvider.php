<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Routing\Router;
use Rinvex\Fort\Providers\FortServiceProvider as BaseFortServiceProvider;

class FortServiceProvider extends BaseFortServiceProvider
{
    protected function overrideMiddleware(Router $router)
    {
        // Do NOT override middleware! We have our own custom middleware already!!
    }

    protected function overrideExceptionHandler()
    {
        // Do NOT override exception handler! We have our own custom exception handler already!!
    }
}
