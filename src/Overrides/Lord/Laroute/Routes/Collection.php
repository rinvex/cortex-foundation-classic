<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Lord\Laroute\Routes;

use Illuminate\Routing\Route;
use Lord\Laroute\Routes\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Get the route information for a given route.
     *
     * @param $route     \Illuminate\Routing\Route
     * @param $filter    string
     * @param $namespace string
     *
     * @return array|null
     */
    protected function getRouteInformation(Route $route, $filter, $namespace): ?array
    {
        $uri = $route->uri();
        $host = $route->domain();
        $name = $route->getName();
        $laroute = $route->getAction('laroute');

        switch ($filter) {
            case 'all':
                if ($laroute === false) {
                    return null;
                }
                break;
            case 'only':
                if ($laroute !== true) {
                    return null;
                }
                break;
        }

        return compact('host', 'uri', 'name');
    }
}
