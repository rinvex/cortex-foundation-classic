<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\PolicyMakeCommand as BasePolicyMakeCommand;

class PolicyMakeCommand extends BasePolicyMakeCommand
{
    use ConsoleMakeModuleCommand;

    /**
     * Replace the User model namespace.
     *
     * @param string $stub
     *
     * @return string
     */
    protected function replaceUserNamespace($stub): string
    {
        if (! $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model')) {
            return $stub;
        }

        return str_replace(
            $this->rootNamespace().'User',
            $userModel,
            $stub
        );
    }
}
