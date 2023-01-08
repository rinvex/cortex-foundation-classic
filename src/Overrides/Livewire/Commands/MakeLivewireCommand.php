<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:livewire')]
class MakeLivewireCommand extends MakeCommand
{
    protected $signature = 'make:livewire {name} {--force} {--inline} {--test} {--stub= : If you have several stubs, stored in subfolders } {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';
}
