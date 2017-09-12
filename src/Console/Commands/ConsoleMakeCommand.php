<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand as BaseConsoleMakeCommand;

class ConsoleMakeCommand extends BaseConsoleMakeCommand
{
    use ConsoleMakeModuleCommand;
}
