<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

class RmCommand extends DeleteCommand
{
    protected $signature = 'livewire:rm {name} {--inline} {--force} {--test} {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
