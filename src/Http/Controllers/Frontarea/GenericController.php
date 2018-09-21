<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Frontarea;

use Cortex\Foundation\Http\Controllers\AbstractController;

class GenericController extends AbstractController
{
    /**
     * Show my country.
     *
     * @return string
     */
    public function country(): string
    {
        return geoip(request()->getClientIp())->iso_code;
    }
}
