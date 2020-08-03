<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;

class DeactivateCommand extends ActivateCommand
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cortex:deactivate {--f|force : Force the operation to run when in production.} {--m|module=* : Specify a module to activate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate application modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        return $this->writeModulesManifest(false);
    }
}
