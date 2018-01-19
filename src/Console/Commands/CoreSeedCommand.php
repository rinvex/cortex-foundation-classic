<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\ArrayInput;

class CoreSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        collect(Artisan::all())->filter(function ($command) {
            return mb_strpos($command->getName(), 'cortex:seed:') !== false;
        })->partition(function ($command) {
            return $command->getName() === 'cortex:seed:fort';
        })->flatten()->each->run(new ArrayInput([]), $this->output);
    }
}
