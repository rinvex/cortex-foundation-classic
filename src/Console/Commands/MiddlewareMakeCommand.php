<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Routing\Console\MiddlewareMakeCommand as BaseMiddlewareMakeCommand;

#[AsCommand(name: 'make:middleware')]
class MiddlewareMakeCommand extends BaseMiddlewareMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
