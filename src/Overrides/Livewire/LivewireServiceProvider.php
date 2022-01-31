<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire;

use Livewire\Commands\StubsCommand;
use Illuminate\Filesystem\Filesystem;
use Livewire\Commands\PublishCommand;
use Livewire\Commands\S3CleanupCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\CpCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\MvCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\RmCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\CopyCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\MakeCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\MoveCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\TouchCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\DeleteCommand;
use Cortex\Foundation\Overrides\Livewire\Commands\DiscoverCommand;
use Livewire\LivewireServiceProvider as BaseLivewireServiceProvider;
use Cortex\Foundation\Overrides\Livewire\Commands\MakeLivewireCommand;

class LivewireServiceProvider extends BaseLivewireServiceProvider
{
    public function register()
    {
        $this->registerConfig();
        $this->registerTestMacros();
        $this->registerLivewireSingleton();
        $this->registerComponentAutoDiscovery();
    }

    protected function registerLivewireSingleton()
    {
        $this->app->singleton(LivewireManager::class);

        $this->app->alias(LivewireManager::class, 'livewire');
    }

    protected function registerRoutes()
    {
        // This is to override the default routes registration.
        // Routes are registered the Cortex way here!
    }

    protected function registerComponentAutoDiscovery()
    {
        // Rather than forcing users to register each individual component,
        // we will auto-detect the component's class based on its kebab-cased
        // alias. For instance: 'examples.foo' => App\Http\Livewire\Examples\Foo

        // We will generate a manifest file so we don't have to do the lookup every time.
        $defaultManifestPath = $this->app['livewire']->isRunningServerless()
            ? '/tmp/storage/bootstrap/cache/components.php'
            : app()->bootstrapPath('cache/components.php');

        $this->app->singleton(LivewireComponentsFinder::class, function () use ($defaultManifestPath) {
            return new LivewireComponentsFinder(
                new Filesystem(),
                config('livewire.manifest_path') ?: $defaultManifestPath,
                ''
            );
        });
    }

    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            MakeLivewireCommand::class, // make:livewire
            MakeCommand::class,         // livewire:make
            TouchCommand::class,        // livewire:touch
            CopyCommand::class,         // livewire:copy
            CpCommand::class,           // livewire:cp
            DeleteCommand::class,       // livewire:delete
            RmCommand::class,           // livewire:rm
            MoveCommand::class,         // livewire:move
            MvCommand::class,           // livewire:mv
            StubsCommand::class,        // livewire:stubs
            DiscoverCommand::class,     // livewire:discover
            S3CleanupCommand::class,    // livewire:configure-s3-upload-cleanup
            PublishCommand::class,      // livewire:publish
        ]);
    }
}
