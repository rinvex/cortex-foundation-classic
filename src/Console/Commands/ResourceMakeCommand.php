<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;

#[AsCommand(name: 'make:resource')]
class ResourceMakeCommand extends BaseResourceMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
