<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;

class CoreAutoloadCommand extends AbstractModuleCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:autoload {--f|force : Force the operation to run when in production.} {--m|module=* : Specify which modules to autoload.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autoload Cortex Modules.';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->process($this->option('module'), ['autoload' => true]);
    }
}
