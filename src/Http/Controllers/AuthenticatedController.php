<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

class AuthenticatedController extends AbstractController
{
    /**
     * Create a new authenticated controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        ! app()->bound('request.guard') || $this->middleware(($guard = app('request.guard')) ? 'auth:'.$guard : 'auth')->except($this->middlewareWhitelist);
    }
}
