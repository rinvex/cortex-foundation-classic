<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as BaseTrustProxies;

class TrustProxies extends BaseTrustProxies
{
    /**
     * Get the trusted proxies.
     *
     * @return array|string|null
     */
    protected function proxies()
    {
        return config('cortex.foundation.route.proxies');
    }
}
