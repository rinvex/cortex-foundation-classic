<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\EventGenerateCommand as BaseEventGenerateCommand;

#[AsCommand(name: 'event:generate')]
class EventGenerateCommand extends BaseEventGenerateCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
