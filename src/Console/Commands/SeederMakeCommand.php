<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand as BaseSeederMakeCommand;

class SeederMakeCommand extends BaseSeederMakeCommand
{
    use ConsoleMakeModuleCommand;

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
        $name = $this->moduleName().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeds'.DIRECTORY_SEPARATOR.$name;

        if (! $this->files->exists($path = $this->laravel['path'].DIRECTORY_SEPARATOR.$this->moduleName().DIRECTORY_SEPARATOR)) {
            throw new \Exception("Invalid path: {$path}");
        }

        return $this->laravel['path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
    }
}
