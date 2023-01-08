<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand as BaseExceptionMakeCommand;

#[AsCommand(name: 'make:exception')]
class ExceptionMakeCommand extends BaseExceptionMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
