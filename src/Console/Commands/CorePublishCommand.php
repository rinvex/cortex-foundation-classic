<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CorePublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish {--force : Overwrite any existing files.} {--R|resource=all}';

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
        $commands = collect(Artisan::all())->filter(function ($command) {
            return mb_strpos($command->getName(), 'cortex:publish:') !== false;
        })->partition(function ($command) {
            return in_array($command->getName(), ['cortex:publish:foundation', 'cortex:publish:auth']);
        })->flatten();

        $progressBar = $this->output->createProgressBar($commands->count());
        $progressBar->setBarCharacter('<fg=green>▒</>');
        $progressBar->setEmptyBarCharacter("<fg=white>▒</>");
        $progressBar->setProgressCharacter("<fg=green>➤</>");
        $progressBar->setFormat("<fg=yellow>$this->description. (Step %current% / %max%)</>\n[%bar%] %percent%%\nElapsed Time: %elapsed%");
        $progressBar->start();

        $output = new BufferedOutput;
        $commands->each(function (Command $command) use ($progressBar, $output) {
            $command->run(new ArrayInput(['--force' => $this->option('force')]), $output);
            $progressBar->advance();
        });

        $progressBar->finish();

        $this->laravel['log']->channel('installer')->debug("\n".$output->fetch());

        $this->line('');
        $this->line('');
    }
}
