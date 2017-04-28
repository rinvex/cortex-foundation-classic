<?php

declare(strict_types=1);

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
}
