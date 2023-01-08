<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

#[AsCommand(name: 'serve')]
class ServeCommand extends BaseServeCommand
{
    use ConfirmableTrait;
}
