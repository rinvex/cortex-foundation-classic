<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as BaseTrustHosts;

class TrustHosts extends BaseTrustHosts
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    public function hosts()
    {
        $hosts = [];
        $domains = route_domains(request()->accessarea());

        foreach ($domains as $domain) {
            $hosts[] = '^(.+\.)?'.preg_quote($domain).'$';
        }

        return $hosts;
    }
}
