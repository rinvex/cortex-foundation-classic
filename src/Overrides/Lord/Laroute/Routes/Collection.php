<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

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
                    return null;
                }
                break;
            case 'only':
                if ($laroute !== true) {
                    return null;
                }
                break;
        }

        return compact('uri', 'name');
    }
}
