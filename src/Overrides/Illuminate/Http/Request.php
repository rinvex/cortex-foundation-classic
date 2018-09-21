<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Http;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url(): string
    {
        return parent::url().(config('cortex.foundation.route.trailing_slash') ? '/' : '');
    }
}
