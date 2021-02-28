<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

class UnauthenticatedController extends AbstractController
{
    /**
     * Create a new unauthenticated controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(($guard = request()->guard()) ? 'guest:'.$guard : 'guest')->except($this->middlewareWhitelist);
    }
}
