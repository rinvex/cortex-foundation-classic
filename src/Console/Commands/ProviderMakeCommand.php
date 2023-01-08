<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ProviderMakeCommand as BaseProviderMakeCommand;

#[AsCommand(name: 'make:provider')]
class ProviderMakeCommand extends BaseProviderMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
