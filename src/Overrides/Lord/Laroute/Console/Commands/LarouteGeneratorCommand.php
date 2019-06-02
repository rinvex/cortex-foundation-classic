<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Lord\Laroute\Console\Commans;

use Lord\Laroute\Console\Commands\LarouteGeneratorCommand as BaseLarouteGeneratorCommand;

class LarouteGeneratorCommand extends BaseLarouteGeneratorCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('');

        try {
            $filePath = $this->generator->compile(
                $this->getTemplatePath(),
                $this->getTemplateData(),
                $this->getFileGenerationPath()
            );

            $this->info("Created: {$filePath}");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->line('');
    }
}
