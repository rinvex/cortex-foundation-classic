<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Cortex\Foundation\Overrides\Livewire\LivewireComponentsFinder;

class FileManipulationCommand extends Command
{
    protected $parser;

    protected function ensureDirectoryExists($path)
    {
        if (! File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, $recursive = true, $force = true);
        }
    }

    public function refreshComponentAutodiscovery()
    {
        app(LivewireComponentsFinder::class)->build();
    }
}
