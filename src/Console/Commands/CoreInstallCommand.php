<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;

class CoreInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install {--f|force : Force the operation to run when in production.} {--r|resource=* : Specify which resources to publish.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Project.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('key:generate', ['--ansi' => true]);
        $this->call('storage:link');

        $this->call('self-diagnosis');

        // Publish assets only if explicitly required, otherwise skip for clean installation
        ! $this->option('resource') || $this->call('cortex:publish', ['--force' => $this->option('force'), '--resource' => $this->option('resource')]);

        $this->call('cortex:migrate', ['--force' => $this->option('force')]);
        $this->call('cortex:seed');

        $this->call('cortex:autoload', ['--force' => $this->option('force')]);
        $this->call('cortex:activate', ['--force' => $this->option('force')]);
    }
}
