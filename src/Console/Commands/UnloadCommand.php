<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Rinvex\Composer\Services\ModuleManifest;

class UnloadCommand extends AutoloadCommand
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cortex:unload {--f|force : Force the operation to run when in production.} {--m|module=* : Specify a module to activate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unload application modules';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('clear-compiled');

        $moduleManifest = new ModuleManifest($this->laravel->getCachedModulesPath());

        collect($this->option('module'))->intersect($this->laravel['request.modules'])->map(function ($attributes, $module) use ($moduleManifest) {
            $attributes['autoload'] = in_array($module, config('rinvex.composer.core')) ? true : false;
            $moduleManifest->add($module, $attributes, true);
        });

        $moduleManifest->persist();
    }
}
