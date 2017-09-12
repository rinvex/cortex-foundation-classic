<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;

class ConfigMakeCommand extends GeneratorCommand
{
    use ConsoleMakeModuleCommand;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new config file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Config';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../../resources/stubs/config.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace_first($this->rootNamespace(), $this->moduleName().DIRECTORY_SEPARATOR.'config', $name);

        if (! $this->files->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName())) {
            throw new \Exception("Invalid path: {$path}");
        }

        return $this->laravel['path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
    }
}
