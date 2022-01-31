<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

class CpCommand extends CopyCommand
{
    protected $signature = 'livewire:cp {name} {new-name} {--inline} {--force} {--test} {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
