<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\ConfirmableTrait;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;
use Illuminate\Foundation\Console\ListenerMakeCommand as BaseListenerMakeCommand;

class ListenerMakeCommand extends BaseListenerMakeCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name): string
    {
        $event = $this->option('event');

        if (! Str::startsWith($event, [
            $this->rootNamespace(),
            'Illuminate',
            '\\',
        ])) {
            $event = $this->rootNamespace().'Events\\'.$event;
        }

        $stub = str_replace(
            'DummyEvent', class_basename($event), $this->defaultBuildClass($name)
        );

        return str_replace(
            'DummyFullEvent', $event, $stub
        );
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function defaultBuildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
}
