<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\ArrayInput;

class CorePublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Foundation Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        collect(Artisan::all())->filter(function ($command) {
            return mb_strpos($command->getName(), 'cortex:publish:') !== false;
        })->partition(function ($command) {
            return in_array($command->getName(), ['cortex:publish:foundation', 'cortex:publish:fort']);
        })->flatten()->each->run(new ArrayInput([]), $this->output);
    }
}