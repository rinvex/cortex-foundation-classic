<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Foundation\PackageManifest;
use Rinvex\Composer\Services\ModuleManifest;

abstract class AbstractModuleCommand extends Command
{
    use ConfirmableTrait;

    /**
     * Execute the console command.
     */
    abstract public function handle();

    /**
     * Process the console command.
     *
     * @param array $attributes
     *
     * @throws \Exception
     *
     * @return void
     *
     */
    protected function process(array $attributes)
    {
        $modules = collect($this->option('module'));

        $packageManifest = app(PackageManifest::class);

        $packages = $packageManifest->getInstalledPackages();

        $moduleManifest = (new ModuleManifest($this->laravel->getCachedModulesPath()))->load();

        if ($modules->isEmpty()) {
            // Activate all modules if none given
            $modules = $packageManifest->getModules();
        }

        $modules->each(function ($module) use ($packages, $attributes, $moduleManifest) {
            if ($manifestAttributes = $moduleManifest->get($module) ?? $packages->firstWhere('name', $module)) {
                $moduleManifest->add($module, [
                    'active' => in_array($module, config('rinvex.composer.always_active')) ? true : Arr::get($attributes, 'active', $manifestAttributes['active'] ?? false),
                    'autoload' => in_array($module, config('rinvex.composer.always_active')) ? true : Arr::get($attributes, 'autoload', $manifestAttributes['autoload'] ?? false),
                    'version' => $manifestAttributes['version'],
                ], true);
            }
        });

        $this->alert('Module loading/activation processed!');

        $moduleManifest->persist();

        $this->call('clear-compiled');
    }
}
