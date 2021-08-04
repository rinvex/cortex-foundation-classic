<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console;

use ReflectionClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Application as Artisan;
use Cortex\Foundation\Console\Commands\MigrateMakeCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * The Artisan commands ignored by auto loading.
     *
     * @var array
     */
    protected $ignoreCommads = [
        MigrateMakeCommand::class,
    ];

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Cortex\Foundation\Bootstrapers\SetRequestForConsole::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load($this->discoverCommands());
    }

    /**
     * Discover the artisan commands for the application.
     *
     * @return array
     */
    public function discoverCommands(): array
    {
        $moduleResources = $this->app['files']->moduleResources('src/Console/Commands', 'directories', 2);

        return collect($moduleResources)->map->getPathname()->toArray();
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @param array|string $paths
     *
     * @return void
     */
    protected function load($paths)
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = ucwords(str_replace(
                ['src/', '/', '.php'],
                ['', '\\', ''],
                Str::after($command->getRealPath(), realpath(app_path()).DIRECTORY_SEPARATOR)
            ), '\\');

            if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract() && ! in_array($command, $this->ignoreCommads)) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}
