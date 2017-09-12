<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;

class MailMakeCommand extends BaseMailMakeCommand
{
    use ConsoleMakeModuleCommand;
}
