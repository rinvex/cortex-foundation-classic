<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as BaseMigrateMakeCommand;

class MigrateMakeCommand extends BaseMigrateMakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:migration {name : The name of the migration.}
        {--module= : The name of the module.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
        {--path= : The location where the migration file should be created.}';

    /**
     * Get migration path (either specified by '--path' option or module location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return $this->laravel->basePath().'/'.$targetPath;
        }

        if (! is_null($module = $this->input->getOption('module')) && $this->laravel->files->exists($modulePath = $this->laravel->path().DIRECTORY_SEPARATOR.$module)) {
            return $modulePath.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
        }

        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
