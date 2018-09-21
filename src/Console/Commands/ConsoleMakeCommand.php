<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand as BaseConsoleMakeCommand;

class ConsoleMakeCommand extends BaseConsoleMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
