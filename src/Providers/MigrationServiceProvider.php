<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Cortex\Foundation\Console\Commands\MigrateMakeCommand;
use Illuminate\Database\MigrationServiceProvider as BaseMigrationServiceProvider;

class MigrationServiceProvider extends BaseMigrationServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migrate' => 'command.migrate',
        'MigrateRollback' => 'command.migrate.rollback',
        'MigrateStatus' => 'command.migrate.status',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'MigrateFresh' => 'command.migrate.fresh',
        'MigrateInstall' => 'command.migrate.install',
        'MigrateRefresh' => 'command.migrate.refresh',
        'MigrateReset' => 'command.migrate.reset',
        'MigrateMake' => 'command.migrate.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();

        $this->registerMigrator();

        $this->registerCreator();

        (! $this->app->runningInConsole() && ! $this->runningInDevzone()) || $this->registerCommands($this->commands);
        ($this->app->environment('production') || (! $this->app->runningInConsole() && ! $this->runningInDevzone())) || $this->registerCommands($this->devCommands);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand(): void
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->app->environment('production')
            ? array_merge(['migrator', 'migration.repository', 'migration.creator'], array_values($this->commands))
            : array_merge(['migrator', 'migration.repository', 'migration.creator'], array_values($this->commands), array_values($this->devCommands));
    }
}
