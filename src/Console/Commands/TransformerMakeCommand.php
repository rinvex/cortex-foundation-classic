<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;

#[AsCommand(name: 'make:transformer')]
class TransformerMakeCommand extends GeneratorCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param string $stub
     * @param string $model
     *
     * @return string
     */
    protected function replaceModel($stub, $model): string
    {
        $model = str_replace('/', '\\', $model);

        $namespaceModel = $this->rootNamespace().'\Models\\'.$model;

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('{{ namespacedModel }}', trim($model, '\\'), $stub);
        } else {
            $stub = str_replace('{{ namespacedModel }}', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};",
            "use {$namespaceModel};",
            $stub
        );

        $model = class_basename(trim($model, '\\'));

        $stub = str_replace('{{ modelVariable }}', $model, $stub);

        return str_replace('{{ modelVariable }}', Str::camel($model), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('model')
            ? __DIR__.'/../../../resources/stubs/transformer.stub'
            : __DIR__.'/../../../resources/stubs/transformer.plain.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Transformers';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the transformer.'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the transformer applies to.'],
            ['module', 'd', InputOption::VALUE_REQUIRED, 'The module name to generate the file within.'],
        ];
    }
}
