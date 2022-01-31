<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

class TouchCommand extends MakeCommand
{
    protected $signature = 'livewire:touch {name} {--force} {--inline} {--test} {--stub=default} {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
