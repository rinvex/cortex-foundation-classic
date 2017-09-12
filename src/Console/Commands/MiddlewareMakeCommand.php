<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Routing\Console\MiddlewareMakeCommand as BaseMiddlewareMakeCommand;

class MiddlewareMakeCommand extends BaseMiddlewareMakeCommand
{
    use ConsoleMakeModuleCommand;
}
