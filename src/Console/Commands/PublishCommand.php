<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:foundation {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Foundation Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn($this->description);
        $this->call('vendor:publish', ['--tag' => 'cortex-foundation-config', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-foundation-views', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-foundation-lang', '--force' => $this->option('force')]);
    }
}
