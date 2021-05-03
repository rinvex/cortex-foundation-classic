<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Diglactic\Breadcrumbs;

use Diglactic\Breadcrumbs\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Register a breadcrumb-generating callback for a page.
     *
     * @param string $name The name of the page.
     * @param callable $callback The callback, which should accept a Generator instance as the first parameter and may
     *     accept additional parameters.
     * @return void
     */
    public function for(string $name, callable $callback): void
    {
        if (isset($this->callbacks[$name])) {
            // throw new DuplicateBreadcrumbException($name);
            return;
        }

        $this->callbacks[$name] = $callback;
    }
}
