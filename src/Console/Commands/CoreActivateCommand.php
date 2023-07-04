<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cortex:activate')]
class CoreActivateCommand extends AbstractModuleCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:activate {--f|force : Force the operation to run when in production.} {--m|module=* : Specify which modules to activate.} {--e|extension=* : Specify which extensions to activate.} {--a|autoload : Autoload modules/extensions before activating.} {--all-modules : activate all modules.} {--all-extensions : activate all extensions.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate Cortex Modules/Extensions.';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->option('autoload') ? $this->process(['autoload' => true, 'active' => true]) : $this->process(['active' => true]);
    }
}
