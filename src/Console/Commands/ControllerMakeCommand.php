<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('parent')) {
            return __DIR__.'/../../../resources/stubs/controller.nested.stub';
        }
        if ($this->option('model')) {
            return __DIR__.'/../../../resources/stubs/controller.model.stub';
        }
        if ($this->option('resource')) {
            return __DIR__.'/../../../resources/stubs/controller.stub';
        }

        return __DIR__.'/../../../resources/stubs/controller.plain.stub';
    }
}
