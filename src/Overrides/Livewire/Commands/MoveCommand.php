<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Illuminate\Support\Facades\File;
use Cortex\Foundation\Traits\ConsoleMakeModuleCommand;

class MoveCommand extends FileManipulationCommand
{
    use ConsoleMakeModuleCommand;

    protected $signature = 'livewire:move {name} {new-name} {--force} {--inline} {--test} {--m|module= : The module name to generate the file within.} {--a|accessarea= : The accessarea to generate the file within.}';

    protected $description = 'Move a Livewire component';

    public function handle()
    {
        $this->parser = new ComponentParser(
            $rootNamespace = $this->rootNamespace(),
            $this->getResourcePath($rootNamespace, 'src/Http/Components'),
            $this->getResourcePath($rootNamespace, 'resources/views'),
            $this->getAccessareaName(),
            $this->argument('name')
        );

        $this->newParser = new ComponentParserFromExistingComponent(
            $rootNamespace = $this->rootNamespace(),
            $this->getResourcePath($rootNamespace, 'src/Http/Components'),
            $this->getResourcePath($rootNamespace, 'resources/views'),
            $this->argument('new-name'),
            $this->parser
        );

        $inline = $this->option('inline');

        $class = $this->renameClass();

        if (! $inline) {
            $view = $this->renameView();
        }

        $test = $this->option('test');

        if ($test) {
            $test = $this->renameTest();
        }
        $this->refreshComponentAutodiscovery();

        $this->line("<options=bold,reverse;fg=green> COMPONENT MOVED </> 🤙\n");
        $class && $this->line("<options=bold;fg=green>CLASS:</> {$this->parser->relativeClassPath()} <options=bold;fg=green>=></> {$this->newParser->relativeClassPath()}");

        if (! $inline) {
            $view && $this->line("<options=bold;fg=green>VIEW:</>  {$this->parser->relativeViewPath()} <options=bold;fg=green>=></> {$this->newParser->relativeViewPath()}");
        }

        if ($test) {
            $test && $this->line("<options=bold;fg=green>Test:</>  {$this->parser->relativeTestPath()} <options=bold;fg=green>=></> {$this->newParser->relativeTestPath()}");
        }
    }

    protected function renameClass()
    {
        if (File::exists($this->newParser->classPath())) {
            $this->line("<options=bold,reverse;fg=red> WHOOPS-IE-TOOTLES </> 😳 \n");
            $this->line("<fg=red;options=bold>Class already exists:</> {$this->newParser->relativeClassPath()}");

            return false;
        }

        $this->ensureDirectoryExists($this->newParser->classPath());

        File::put($this->newParser->classPath(), $this->newParser->classContents());

        return File::delete($this->parser->classPath());
    }

    protected function renameView()
    {
        $newViewPath = $this->newParser->viewPath();

        if (File::exists($newViewPath)) {
            $this->line("<fg=red;options=bold>View already exists:</> {$this->newParser->relativeViewPath()}");

            return false;
        }

        $this->ensureDirectoryExists($newViewPath);

        File::move($this->parser->viewPath(), $newViewPath);

        return $newViewPath;
    }

    protected function renameTest()
    {
        $newTestPath = $this->newParser->testPath();
        if (File::exists($newTestPath)) {
            $this->line("<fg=red;options=bold>Test already exists:</> {$this->newParser->relativeViewPath()}");

            return false;
        }
        $this->ensureDirectoryExists($newTestPath);
        File::move($this->parser->testPath(), $newTestPath);

        return $newTestPath;
    }
}
