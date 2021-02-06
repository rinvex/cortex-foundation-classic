<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Artisan;

class ExecuteDumpCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:execute:dump {--f|force : Overwrite any existing files.}';

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
    public function handle(): void
    {
        $artisan = collect(Artisan::all());

        ! $artisan->has('ide-helper:generate') || $this->call('ide-helper:generate', ['--ansi' => true]);

        ! $artisan->has('ide-helper:meta') || $this->call('ide-helper:meta', ['--ansi' => true]);
    }
}
