<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ScopeMakeCommand as BaseScopeMakeCommand;

class ScopeMakeCommand extends BaseScopeMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
