<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Rinvex\Support\Traits\ConsoleTools;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\MigrationServiceProvider as BaseMigrationServiceProvider;

class MigrationServiceProvider extends BaseMigrationServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migrate' => MigrateCommand::class,
        'MigrateRollback' => RollbackCommand::class,
        'MigrateStatus' => StatusCommand::class,
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'MigrateFresh' => FreshCommand::class,
        'MigrateInstall' => InstallCommand::class,
        'MigrateRefresh' => RefreshCommand::class,
        'MigrateReset' => ResetCommand::class,
        'MigrateMake' => MigrateMakeCommand::class,
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

        $this->registerCommands($this->commands);
        $this->app->environment('production') || $this->registerCommands($this->devCommands);
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
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton(MigrateMakeCommand::class, function ($app) {
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
