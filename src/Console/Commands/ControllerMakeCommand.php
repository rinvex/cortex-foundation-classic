<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use ConsoleMakeModuleCommand;
}
