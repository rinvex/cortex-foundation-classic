<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ObserverMakeCommand as BaseObserverMakeCommand;

class ObserverMakeCommand extends BaseObserverMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

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
