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

        $this->middleware($this->getAuthMiddleware())->except($this->middlewareWhitelist]);
    }
}
