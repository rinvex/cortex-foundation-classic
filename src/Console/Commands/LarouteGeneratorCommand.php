<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console;

use Lord\Laroute\Console\Commands\LarouteGeneratorCommand as BaseLarouteGeneratorCommand;

class LarouteGeneratorCommand extends BaseLarouteGeneratorCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::fire();
    }
}
