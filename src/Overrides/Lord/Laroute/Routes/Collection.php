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
     * @return array
     */
    protected function getRouteInformation(Route $route, $filter, $namespace)
    {
        $uri = $route->uri();
        $name = $route->getName();
        $laroute = array_get($route->getAction(), 'laroute', null);

        switch ($filter) {
            case 'all':
                if ($laroute === false) {
                    return;
                }
                break;
            case 'only':
                if ($laroute !== true) {
                    return;
                }
                break;
        }

        return compact('uri', 'name');
    }
}
