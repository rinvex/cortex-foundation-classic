<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ProviderMakeCommand as BaseProviderMakeCommand;

class ProviderMakeCommand extends BaseProviderMakeCommand
{
    use ConsoleMakeModuleCommand;
}
