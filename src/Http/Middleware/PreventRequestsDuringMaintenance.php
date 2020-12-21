<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as BasePreventRequestsDuringMaintenance;

class PreventRequestsDuringMaintenance extends BasePreventRequestsDuringMaintenance
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
