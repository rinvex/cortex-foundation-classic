<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand as BaseExceptionMakeCommand;

class ExceptionMakeCommand extends BaseExceptionMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
