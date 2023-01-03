<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Collective\Html;

use Collective\Html\FormBuilder as BaseFormBuilder;

class FormBuilder extends BaseFormBuilder
{
    /**
     * Get the form appendage for the given method.
     *
     * @override append DFS Token to forms.
     *
     * @param string $method
     *
     * @return string
     */
    protected function getAppendage($method)
    {
        [$method, $appendage] = [mb_strtoupper($method), ''];

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
            $appendage .= dfs_field();
        }

        return $appendage;
    }
}
