<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ComponentMakeCommand as BaseComponentMakeCommand;

class ComponentMakeCommand extends BaseComponentMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
