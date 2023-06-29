<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cortex:unload')]
class CoreUnloadCommand extends AbstractModuleCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:unload {--f|force : Force the operation to run when in production.} {--m|module=* : Specify which modules to unload.} {--e|extension=* : Specify which extensions to unload.} {--d|deactivate : Deactivate modules/extensions after unloading.} {--all-modules : unload all modules.} {--all-extensions : unload all extensions.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unload Cortex Modules/Extensions.';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->process(['autoload' => false]);

        ! $this->option('deactivate') || $this->call('cortex:deactivate');
    }
}
