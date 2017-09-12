<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;

class RequestMakeCommand extends BaseRequestMakeCommand
{
    use ConsoleMakeModuleCommand;
}
