<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

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
     * @param  string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace_first($this->rootNamespace(), $this->moduleName().DIRECTORY_SEPARATOR.'src', $name);

        if (! $this->files->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName())) {
            throw new \Exception("Invalid path: {$path}");
        }

        return $this->laravel['path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->rootNamespace ?? $this->rootNamespace = implode('\\', array_map('ucfirst', explode('/', trim($this->moduleName()))));
    }

    /**
     * Get the module name for the class.
     *
     * @return string
     */
    protected function moduleName()
    {
        return $this->moduleName ?? $this->input->getOption('module') ?? $this->moduleName = $this->ask('What is your module?');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['module', 'd', InputOption::VALUE_REQUIRED, 'The module name to generate the file within.'],
        ]);
    }
}
