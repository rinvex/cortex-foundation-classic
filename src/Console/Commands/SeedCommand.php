<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Cortex\Foundation\Database\Seeders\CortexFoundationSeeder;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:foundation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Foundation Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('db:seed', ['--class' => CortexFoundationSeeder::class]);

        $this->line('');
    }
}
