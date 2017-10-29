<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;

class CoreInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Project.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn($this->description);
        $this->call('cortex:migrate');
        $this->call('cortex:seed');
        $this->call('cortex:publish');
    }
}
