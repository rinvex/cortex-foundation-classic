<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\CastMakeCommand as BaseCastMakeCommand;

class CastMakeCommand extends BaseCastMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
