<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * {@inheritdoc}
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(glob(app_path('*/*/src/Console/Commands')));
    }

    /**
     * {@inheritdoc}
     */
    protected function load($paths)
    {
        $paths = array_unique(is_array($paths) ? $paths : (array) $paths);

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = str_replace(' ', '\\', ucwords(str_replace(['.php', 'src/', '/'], ['', '', ' '], Str::after($command->getPathname(), app_path().'/'))));

            if (is_subclass_of($command, Command::class) && ! property_exists($command, 'webConsole')) {
                Artisan::starting(function (Artisan $artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}
