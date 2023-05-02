<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Database\Console\ShowModelCommand as BaseShowModelCommand;

#[AsCommand(name: 'model:show')]
class ShowModelCommand extends BaseShowModelCommand
{
}
