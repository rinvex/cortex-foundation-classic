<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\PolicyMakeCommand as BasePolicyMakeCommand;

class PolicyMakeCommand extends BasePolicyMakeCommand
{
    use ConfirmableTrait;
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

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $rootNamespace.'Models\\'.$model;
    }
}
