<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait ConsoleMakeModuleCommand
{
    /**
     * The module name.
     *
     * @var string
     */
    protected $moduleName;

    /**
     * The root namespace.
     *
     * @var string
     */
    protected $rootNamespace;

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), $this->moduleName().DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR, $name);

        if (! $this->files->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName())) {
            throw new \Exception("Invalid path: {$path}");
        }

        return $this->laravel['path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
    }

    /**
     * Get the destination component path.
     *
     * @param string $module
     * @param string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getResourcePath(string $module, string $name): string
    {
        return $this->getModulePath($module).$name.DIRECTORY_SEPARATOR;
    }

    /**
     * Get the destination module path.
     *
     * @param string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getModulePath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), $this->moduleName().DIRECTORY_SEPARATOR, $name);

        if (! $this->files->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName())) {
            throw new \Exception("Invalid path: {$path}");
        }

        return $this->laravel['path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->rootNamespace ?? $this->rootNamespace = implode('\\', array_map('ucfirst', explode('/', trim($this->moduleName())))).'\\';
    }

    /**
     * Get the module name for the class.
     *
     * @return string
     */
    protected function moduleName(): string
    {
        return $this->moduleName ?? $this->input->getOption('module') ?? $this->moduleName = $this->ask('What is your module?');
    }

    /**
     * Get the accessarea name for the resource.
     *
     * @return string
     */
    protected function getAccessareaName(): string
    {
        return $this->accessarea ?? $this->input->getOption('accessarea') ?? $this->accessarea = $this->choice('What is your accessarea?', app('accessareas')->pluck('slug')->toArray());
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['module', 'd', InputOption::VALUE_REQUIRED, 'The module name to generate the file within.'],
        ]);
    }
}
