<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use ConsoleMakeModuleCommand;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('parent')) {
            return __DIR__.'/../../../resources/stubs/controller.nested.stub';
        } elseif ($this->option('model')) {
            return __DIR__.'/../../../resources/stubs/controller.model.stub';
        } elseif ($this->option('resource')) {
            return __DIR__.'/../../../resources/stubs/controller.stub';
        }

        return __DIR__.'/../../../resources/stubs/controller.plain.stub';
    }
}
