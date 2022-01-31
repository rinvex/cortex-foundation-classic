<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Cortex\Foundation\Overrides\Livewire\LivewireComponentsFinder;

use Illuminate\Console\Command;

class DiscoverCommand extends Command
{
    protected $signature = 'livewire:discover';

    protected $description = 'Regenerate Livewire component auto-discovery manifest';

    public function handle()
    {
        app(LivewireComponentsFinder::class)->build();

        $this->info('Livewire auto-discovery manifest rebuilt!');
    }
}
