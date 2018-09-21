<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\RuleMakeCommand as BaseRuleMakeCommand;

class RuleMakeCommand extends BaseRuleMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
