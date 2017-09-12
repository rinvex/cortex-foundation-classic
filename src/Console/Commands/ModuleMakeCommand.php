<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ModuleMakeCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:module {name : The name of the module.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module structure';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Module';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->getNameInput();

        $path = app_path($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ($this->files->exists($path)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $stubs = __DIR__.'/../../../resources/stubs/module';
        $this->processStubs($stubs, $path);
        $this->generateSamples();

        $this->info($this->type.' created successfully.');
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Generate code samples.
     *
     * @return void
     */
    protected function generateSamples()
    {
        $this->call('make:config', ['name' => 'config', '--module' => $this->getNameInput()]);
        $this->call('make:model', ['name' => 'Test', '--module' => $this->getNameInput()]);
        $this->call('make:policy', ['name' => 'TestPolicy', '--module' => $this->getNameInput()]);
        $this->call('make:provider', ['name' => ucfirst(str_after($this->getNameInput(), '/')).'ServiceProvider', '--module' => $this->getNameInput()]);
        $this->call('make:command', ['name' => 'TestCommand', '--module' => $this->getNameInput()]);
        $this->call('make:controller', ['name' => 'TestController', '--module' => $this->getNameInput()]);
        $this->call('make:request', ['name' => 'TestRequest', '--module' => $this->getNameInput()]);
        $this->call('make:middleware', ['name' => 'TestMiddleware', '--module' => $this->getNameInput()]);
        $this->call('make:transformer', ['name' => 'TestTransformer', '--model' => 'Test', '--module' => $this->getNameInput()]);
        $this->call('make:datatable', ['name' => 'TestDatatable', '--model' => 'Test', '--transformer' => 'TestTransformer', '--module' => $this->getNameInput()]);
        //$this->call('make:config', ['name' => 'config', '--module' => $this->getNameInput()]);
        //$this->call('make:config', ['name' => 'config', '--module' => $this->getNameInput()]);
        //$this->call('make:config', ['name' => 'config', '--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
        //$this->call('make:config', ['--module' => $this->getNameInput()]);
    }

    /**
     * Process stubs placeholders.
     *
     * @param  string  $stubs
     * @param  string  $path
     *
     * @return void
     */
    protected function processStubs($stubs, $path)
    {
        $this->makeDirectory($path);
        $this->files->copyDirectory($stubs, $path);

        $files = [
            ($phpunit = $path.DIRECTORY_SEPARATOR.'phpunit.xml.dist') => $this->files->get($phpunit),
            ($composer = $path.DIRECTORY_SEPARATOR.'composer.json') => $this->files->get($composer),
            ($changelog = $path.DIRECTORY_SEPARATOR.'CHANGELOG.md') => $this->files->get($changelog),
            ($readme = $path.DIRECTORY_SEPARATOR.'README.md') => $this->files->get($readme),
        ];

        $module = ucfirst(str_after($this->getNameInput(), '/'));
        $name = implode(' ', array_map('ucfirst', explode('/', $this->getNameInput())));
        $jsonNamespace = implode('\\\\', array_map('ucfirst', explode('/', $this->getNameInput())));

        foreach ($files as $key => &$file) {
            $file = str_replace('DummyModuleName', $name, $file);
            $file = str_replace('Dummy\\Module', $jsonNamespace, $file);
            $file = str_replace('DummyModuleServiceProvider', $jsonNamespace."\\\\Providers\\\\{$module}ServiceProvider", $file);

            $file = str_replace('dummy/module', $this->getNameInput(), $file);
            $file = str_replace('dummy-module', str_replace('/', '-', $this->getNameInput()), $file);
            $file = str_replace('dummy:module', str_replace('/', ':', $this->getNameInput()), $file);
            $file = str_replace('dummy.module', str_replace('/', '.', $this->getNameInput()), $file);

            $this->files->put($key, $file);
        }
    }

    /**
     * Get the desired class name from the input.
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        if (strpos($name, '/') === false) {
            throw new \Exception('Module name must consist of two segments: vendor/module');
        }

        return $name;
    }
}
