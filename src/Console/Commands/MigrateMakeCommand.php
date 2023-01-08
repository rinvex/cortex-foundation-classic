<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as BaseMigrateMakeCommand;

#[AsCommand(name: 'make:migration')]
class MigrateMakeCommand extends BaseMigrateMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:migration {name : The name of the migration.}
        {--m|module= : The module name to generate the file within.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}';

    /**
     * Write the migration file to disk.
     *
     * @param string $name
     * @param string $table
     * @param bool   $create
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function writeMigration($name, $table, $create): void
    {
        $this->makeDirectory($this->getMigrationPath());

        parent::writeMigration($name, $table, $create);
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path): string
    {
        if (! $this->laravel['files']->isDirectory($path)) {
            $this->laravel['files']->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Get migration path (either specified by '--path' or '--module' options).
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        if (! $this->laravel['files']->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName())) {
            throw new \Exception("Invalid path: $path");
        }

        return $path.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
    }
}
