<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Cortex\Fort\Traits\AbilitySeeder;
use Cortex\Fort\Traits\BaseFortSeeder;

class SeedCommand extends Command
{
    use AbilitySeeder;
    use BaseFortSeeder;

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
    protected $description = 'Seed Default Cortex Foundation data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->ensureExistingFortTables()) {
            $this->seedAbilities(realpath(__DIR__.'/../../../resources/data/abilities.json'));
        }
    }
}
