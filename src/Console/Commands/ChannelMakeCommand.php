<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ChannelMakeCommand as BaseChannelMakeCommand;

class ChannelMakeCommand extends BaseChannelMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
