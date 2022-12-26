<?php

namespace Cortex\Foundation\Overrides\Collective\Html;

use Cortex\Foundation\Support\DfsToken;
use Collective\Html\FormBuilder as BaseFormBuilder;

class FormBuilder extends BaseFormBuilder
{
    /**
     * Generate a hidden field with the current DFS token.
     *
     * @return string
     */
    public function dfsToken(): string
    {
        return $this->hidden('_dfs_token', app(DfsToken::class)->token());
    }

    /**
     * Get the form appendage for the given method.
     *
     * @override append DFS Token to forms.
     *
     * @param  string $method
     *
     * @return string
     */
    protected function getAppendage($method)
    {
        [$method, $appendage] = [strtoupper($method), ''];

        // If the HTTP method is in this list of spoofed methods, we will attach the
        // method spoofer hidden input to the form. This allows us to use regular
        // form to initiate PUT and DELETE requests in addition to the typical.
        if (in_array($method, $this->spoofedMethods)) {
            $appendage .= $this->hidden('_method', $method);
        }

        // If the method is something other than GET we will go ahead and attach the
        // CSRF token to the form, as this can't hurt and is convenient to simply
        // always have available on every form the developers creates for them.
        if ($method !== 'GET') {
            $appendage .= $this->token();
            $appendage .= $this->dfsToken();
        }

        return $appendage;
    }
}
