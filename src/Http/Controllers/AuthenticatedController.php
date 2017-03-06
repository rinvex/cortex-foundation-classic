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

namespace Cortex\Foundation\Http\Controllers;

class AuthenticatedController extends AbstractController
{
    /**
     * Create a new manage persistence controller instance.
     */
    public function __construct()
    {
        $this->middleware($this->getAuthMiddleware(), ['except' => $this->middlewareWhitelist]);
    }
}
