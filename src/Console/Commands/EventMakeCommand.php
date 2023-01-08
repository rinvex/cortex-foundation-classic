<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\EventMakeCommand as BaseEventMakeCommand;

#[AsCommand(name: 'make:event')]
class EventMakeCommand extends BaseEventMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
