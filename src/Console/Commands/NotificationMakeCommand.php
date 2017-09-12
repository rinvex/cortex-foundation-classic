<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;

class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    use ConsoleMakeModuleCommand;
}
