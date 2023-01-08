<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\CastMakeCommand as BaseCastMakeCommand;

#[AsCommand(name: 'make:cast')]
class CastMakeCommand extends BaseCastMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;
}
