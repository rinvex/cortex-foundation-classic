<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;

#[AsCommand(name: 'make:request')]
class RequestMakeCommand extends BaseRequestMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
