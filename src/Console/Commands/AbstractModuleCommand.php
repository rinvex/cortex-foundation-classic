<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Rinvex\Composer\Models\Manifest;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Foundation\PackageManifest;

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
     */
    protected function process(array $attributes)
    {
        $packageManifest = app(PackageManifest::class);
        $packages = $packageManifest->getInstalledPackages();

        foreach (['module', 'extension'] as $moduleType) {
            if (($allModules = $this->option("all-{$moduleType}s")) && ! $this->confirmToProceed('No modules passed, are you sure you want to process all modules? [y|N]')) {
                return 1;
            }

            $modules = $allModules ? collect($packageManifest->{'get'.ucfirst($moduleType).'Names'}()) : collect($this->option($moduleType));
            $moduleManifest = (new Manifest(config("rinvex.composer.cortex-{$moduleType}.manifest")))->load();

            $modules->each(function ($module) use ($packages, $attributes, $moduleManifest, $moduleType) {
                if ($moduleAttributes = $moduleManifest->get($module) ?? $packages->firstWhere('name', $module)) {
                    $isAlwaysActive = in_array($module, config("rinvex.composer.{$moduleType}.always_active"));
                    $isSetAutoload = Arr::get($attributes, 'autoload', $moduleAttributes['autoload'] ?? false);
                    $isSetActive = Arr::get($attributes, 'active', $moduleAttributes['active'] ?? false);

                    $moduleManifest->add($module, $moduleType === 'extension'
                        ? ['active' => $isAlwaysActive ? true : $isSetActive, 'autoload' => $isAlwaysActive ? true : $isSetAutoload, 'version' => $moduleAttributes['version'], 'extends' => $moduleAttributes['extends'] ?? $moduleAttributes['extra']['cortex']['extends'] ?? null]
                        : ['active' => $isAlwaysActive ? true : $isSetActive, 'autoload' => $isAlwaysActive ? true : $isSetAutoload, 'version' => $moduleAttributes['version']], true);
                }
            })->whenNotEmpty(fn ($modules) => $moduleManifest->persist() || $this->alert(ucfirst(Str::plural($moduleType)).' processed!'), fn () => $this->components->info('No '.ucfirst(Str::plural($moduleType)).' to process!'));
        }

        $this->call('clear-compiled');
    }
}
