<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'livewire:mv')]
class MvCommand extends MoveCommand
{
    protected $signature = 'livewire:mv {name} {new-name} {--inline} {--force} {--test} {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
