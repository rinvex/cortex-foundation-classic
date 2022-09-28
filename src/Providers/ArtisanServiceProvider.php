<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Rinvex\Support\Traits\ConsoleTools;
use Illuminate\Database\Console\DbCommand;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Foundation\Console\ViewCacheCommand;
use Illuminate\Foundation\Console\ViewClearCommand;
use Illuminate\Foundation\Console\EventClearCommand;
use Illuminate\Foundation\Console\RouteCacheCommand;
use Illuminate\Foundation\Console\RouteClearCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Foundation\Console\ConfigCacheCommand;
use Illuminate\Foundation\Console\ConfigClearCommand;
use Illuminate\Foundation\Console\EnvironmentCommand;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleTestCommand;
use Illuminate\Console\Scheduling\ScheduleWorkCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand;
use Illuminate\Foundation\Console\OptimizeClearCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand;
use Illuminate\Console\Scheduling\ScheduleClearCacheCommand;
use Illuminate\Queue\Console\WorkCommand as QueueWorkCommand;
use Illuminate\Cache\Console\ClearCommand as CacheClearCommand;
use Illuminate\Queue\Console\ClearCommand as QueueClearCommand;
use Illuminate\Queue\Console\RetryCommand as QueueRetryCommand;
use Illuminate\Cache\Console\ForgetCommand as CacheForgetCommand;
use Illuminate\Queue\Console\ListenCommand as QueueListenCommand;
use Illuminate\Queue\Console\MonitorCommand as QueueMonitorCommand;
use Illuminate\Queue\Console\RestartCommand as QueueRestartCommand;
use Illuminate\Queue\Console\ListFailedCommand as ListFailedQueueCommand;
use Illuminate\Queue\Console\RetryBatchCommand as QueueRetryBatchCommand;
use Illuminate\Queue\Console\FlushFailedCommand as FlushFailedQueueCommand;
use Illuminate\Queue\Console\ForgetFailedCommand as ForgetFailedQueueCommand;
use Illuminate\Queue\Console\PruneBatchesCommand as QueuePruneBatchesCommand;
use Illuminate\Queue\Console\PruneFailedJobsCommand as QueuePruneFailedJobsCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as BaseArtisanServiceProvider;

class ArtisanServiceProvider extends BaseArtisanServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => CacheClearCommand::class,
        'CacheForget' => CacheForgetCommand::class,
        'ClearCompiled' => ClearCompiledCommand::class,
        'ClearResets' => ClearResetsCommand::class,
        'ConfigCache' => ConfigCacheCommand::class,
        'ConfigClear' => ConfigClearCommand::class,
        'Db' => DbCommand::class,
        'DbPrune' => PruneCommand::class,
        'DbWipe' => WipeCommand::class,
        'Down' => DownCommand::class,
        'Environment' => EnvironmentCommand::class,
        //'EventCache' => EventCacheCommand::class,
        'EventClear' => EventClearCommand::class,
        //'EventList' => EventListCommand::class,
        //'KeyGenerate' => KeyGenerateCommand::class,
        'Optimize' => OptimizeCommand::class,
        'OptimizeClear' => OptimizeClearCommand::class,
        'PackageDiscover' => PackageDiscoverCommand::class,
        'QueueClear' => QueueClearCommand::class,
        'QueueFailed' => ListFailedQueueCommand::class,
        'QueueFlush' => FlushFailedQueueCommand::class,
        'QueueForget' => ForgetFailedQueueCommand::class,
        'QueueListen' => QueueListenCommand::class,
        'QueueMonitor' => QueueMonitorCommand::class,
        'QueuePruneBatches' => QueuePruneBatchesCommand::class,
        'QueuePruneFailedJobs' => QueuePruneFailedJobsCommand::class,
        'QueueRestart' => QueueRestartCommand::class,
        'QueueRetry' => QueueRetryCommand::class,
        'QueueRetryBatch' => QueueRetryBatchCommand::class,
        'QueueWork' => QueueWorkCommand::class,
        'RouteCache' => RouteCacheCommand::class,
        'RouteClear' => RouteClearCommand::class,
        'RouteList' => RouteListCommand::class,
        'SchemaDump' => DumpCommand::class,
        'Seed' => SeedCommand::class,
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleList' => ScheduleListCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'ScheduleClearCache' => ScheduleClearCacheCommand::class,
        'ScheduleTest' => ScheduleTestCommand::class,
        'ScheduleWork' => ScheduleWorkCommand::class,
        //'StorageLink' => StorageLinkCommand::class,
        'Up' => UpCommand::class,
        'ViewCache' => ViewCacheCommand::class,
        'ViewClear' => ViewClearCommand::class,
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommands($this->commands);
        $this->app->environment('production') || $this->registerCommands($this->devCommands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->app->environment('production') ? $this->commands :
            array_merge(array_values($this->commands), array_values($this->devCommands));
    }

    /**
     * Register the given commands.
     *
     * @param array $commands
     *
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach ($commands as $commandName => $command) {
            $method = "register{$commandName}Command";

            if (method_exists($this, $method)) {
                $this->{$method}();
            } else {
                $this->app->singleton($command);
            }
        }

        $this->commands(array_values($commands));
    }
}
