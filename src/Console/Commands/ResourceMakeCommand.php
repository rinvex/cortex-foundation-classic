<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;

class ResourceMakeCommand extends BaseResourceMakeCommand
{
    use ConsoleMakeModuleCommand;
}
