<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;

#[AsCommand(name: 'make:mail')]
class MailMakeCommand extends BaseMailMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
