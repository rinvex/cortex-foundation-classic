<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand as BaseConsoleMakeCommand;

#[AsCommand(name: 'make:command')]
class ConsoleMakeCommand extends BaseConsoleMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
