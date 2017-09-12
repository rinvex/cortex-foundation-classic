<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\EventMakeCommand as BaseEventMakeCommand;

class EventMakeCommand extends BaseEventMakeCommand
{
    use ConsoleMakeModuleCommand;
}
