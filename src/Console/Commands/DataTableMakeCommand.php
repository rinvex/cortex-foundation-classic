<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;

class DataTableMakeCommand extends GeneratorCommand
{
    use ConfirmableTrait;
    use ConsoleMakeModuleCommand;

    /**
     * The model name.
     *
     * @var string
     */
    protected $modelName;

    /**
     * The transformer name.
     *
     * @var string
     */
    protected $transformerName;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:datatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new datatable class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Datatable';

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

        $model = $this->option('model') ?? $this->modelName = $this->ask('What is your model?');

        $transformer = $this->option('transformer') ?? $this->transformerName = $this->ask('What is your transformer?');

        return $this->replaceClasses($stub, $model, $transformer);
    }

    /**
     * Replace the model and transformer for the given stub.
     *
     * @param string $stub
     * @param string $model
     * @param string $transformer
     *
     * @return string
     */
    protected function replaceClasses($stub, $model, $transformer = null): string
    {
        if ($transformer) {
            $transformer = str_replace('/', '\\', $transformer);

            $namespaceTransformer = $this->rootNamespace().'\Transformers\\'.$transformer;

            if (Str::startsWith($transformer, '\\')) {
                $stub = str_replace('NamespacedDummyTransformer', trim($transformer, '\\'), $stub);
            } else {
                $stub = str_replace('NamespacedDummyTransformer', $namespaceTransformer, $stub);
            }

            $stub = str_replace(
                "use {$namespaceTransformer};\nuse {$namespaceTransformer};", "use {$namespaceTransformer};", $stub
            );

            $transformer = class_basename(trim($transformer, '\\'));

            $stub = str_replace('DummyTransformer', $transformer, $stub);

            $stub = str_replace('dummyTransformer', Str::camel($transformer), $stub);
        }

        $model = str_replace('/', '\\', $model);

        $namespaceModel = $this->rootNamespace().'\Models\\'.$model;

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('NamespacedDummyModel', trim($model, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyModel', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};", "use {$namespaceModel};", $stub
        );

        $model = class_basename(trim($model, '\\'));

        $stub = str_replace('DummyModel', $model, $stub);

        return str_replace('dummyModel', Str::camel($model), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../../resources/stubs/datatable.stub';
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
        return $rootNamespace.'\Datatables';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the datatable.'],
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
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model that the datatable applies to.'],
            ['module', 'd', InputOption::VALUE_REQUIRED, 'The module name to generate the file within.'],
            ['transformer', 't', InputOption::VALUE_REQUIRED, 'The transformer that the datatable applies to.'],
        ];
    }
}
