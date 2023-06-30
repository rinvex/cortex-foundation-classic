<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console;

use ReflectionClass;
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
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}('bootstrap/schedule.php');

            collect($resources)
                ->prioritizeLoading()
                ->each(fn ($file) => (require $file)($schedule));
        }
    }

    /**
     * Discover and register all the application commands.
     *
     * @return void
     */
    protected function commands()
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->app['files']->{"{$moduleType}Resources"}('src/Console/Commands', 'directories', 2);
            $paths = array_filter(array_unique(collect($resources)->map->getPathname()->toArray()), fn ($path) => is_dir($path));
            $configPath = config("rinvex.composer.cortex-{$moduleType}.path");

            if (empty($paths)) {
                return;
            }

            foreach ((new Finder())->in($paths)->files() as $command) {
                $command = ucwords(str_replace(
                    ['src/', '/', '.php'],
                    ['', '\\', ''],
                    Str::after($command->getRealPath(), $configPath.'/')
                ), '\\');

                if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract() && ! in_array($command, $this->ignoreCommads)) {
                    Artisan::starting(function ($artisan) use ($command) {
                        $artisan->resolve($command);
                    });
                }
            }
        }
    }
}
