<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\JobMakeCommand as BaseJobMakeCommand;

class JobMakeCommand extends BaseJobMakeCommand
{
    use ConsoleMakeModuleCommand;
}
