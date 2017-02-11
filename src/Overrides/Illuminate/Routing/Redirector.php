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

namespace Cortex\Foundation\Overrides\Illuminate\Routing;

use Illuminate\Routing\Redirector as BaseRedirector;

class Redirector extends BaseRedirector
{
    /**
     * {@inheritdoc}
     */
    public function route($route, $parameters = [], $status = 302, $headers = [])
    {
        return ($previousUrl = $this->generator->getRequest()->get('previous_url'))
            ? $this->to($previousUrl) : parent::route($route, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param string $path
     * @param int    $status
     * @param array  $headers
     * @param bool   $secure
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toBackend($path, $status = 302, $headers = [], $secure = null)
    {
        $path = $this->generator->toBackend($path, [], $secure);

        return $this->createRedirect($path, $status, $headers);
    }
}
