<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\JobMakeCommand as BaseJobMakeCommand;

#[AsCommand(name: 'make:job')]
class JobMakeCommand extends BaseJobMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
