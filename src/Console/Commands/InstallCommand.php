<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install:foundation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Foundation Module.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('cortex:migrate:foundation');
        $this->call('cortex:publish:foundation');
    }
}
