<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Routing;

use Illuminate\Routing\RouteUrlGenerator as BaseRouteUrlGenerator;

class RouteUrlGenerator extends BaseRouteUrlGenerator
{
    /**
     * Add a query string to the URI.
     *
     * @param string $uri
     * @param array  $parameters
     *
     * @return mixed|string
     */
    protected function addQueryString($uri, array $parameters)
    {
        // If the URI has a fragment we will move it to the end of this URI since it will
        // need to come after any query string that may be added to the URL else it is
        // not going to be available. We will remove it then append it back on here.
        if (! is_null($fragment = parse_url($uri, PHP_URL_FRAGMENT))) {
            $uri = preg_replace('/#.*/', '', $uri);
        }

        $uri .= (config('cortex.foundation.route.trailing_slash') ? '/' : '').$this->getRouteQueryString($parameters);

        return is_null($fragment) ? $uri : $uri."#{$fragment}";
    }
}
