<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as BaseTrimStrings;

class TrimStrings extends BaseTrimStrings
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
