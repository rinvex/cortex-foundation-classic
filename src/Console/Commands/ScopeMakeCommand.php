<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ScopeMakeCommand as BaseScopeMakeCommand;

#[AsCommand(name: 'make:scope')]
class ScopeMakeCommand extends BaseScopeMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
