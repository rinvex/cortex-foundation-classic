<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;

#[AsCommand(name: 'make:notification')]
class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
