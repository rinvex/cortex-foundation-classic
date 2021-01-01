<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\ConfirmableTrait;
use Rinvex\Composer\Services\ModuleManifest;

abstract class AbstractModuleCommand extends Command
{
    use ConfirmableTrait;

    /**
     * $files.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Composer installed module attributes.
     *
     * @var array
     */
    protected $installedModule;

    /**
     * __construct.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    abstract public function handle();

    /**
     * Process the console command.
     *
     * @param array $modules
     * @param array $attributes
     *
     * @throws \Exception
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return void
     */
    protected function process(array $modules, array $attributes)
    {
        $this->call('clear-compiled');

        $moduleManifest = new ModuleManifest($this->laravel->getCachedModulesPath());

        collect($modules)->each(function ($module) use ($attributes, $moduleManifest) {
            if ($manifestAttributes = $moduleManifest->load()->get($module)) {
                $manifestAttributes['active'] = true;
                $moduleManifest->add($module, $manifestAttributes, true);
            } elseif ($this->isComposerModuleInstalled($module)) {
                $moduleManifest->add($module, $this->getComposerModuleAttributes($module, $attributes));
            }
        });

        $this->alert('Module loading/activation processed!');

        $moduleManifest->persist();
    }

    /**
     * Check if given module is installed by composer.
     *
     * @param string $module
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return ?array
     */
    protected function isComposerModuleInstalled(string $module): ?array
    {
        if ($this->files->exists($path = $this->laravel->basePath('vendor/composer/installed.json'))) {
            $installed = json_decode($this->files->get($path), true);

            $this->installedModule = collect($installed['packages'] ?? $installed)->firstWhere('name', $module);
        }

        return $this->installedModule ?? null;
    }

    /**
     * Get module attributes for the given module name.
     *
     * @param string $module
     * @param array  $attributes
     *
     * @return array
     */
    protected function getComposerModuleAttributes(string $module, array $attributes): array
    {
        return [
            'active' => in_array($module, config('rinvex.composer.always_active')) ? true : Arr::get($attributes, 'active', false),
            'autoload' => in_array($module, config('rinvex.composer.always_active')) ? true : Arr::get($attributes, 'autoload', false),
            'version' => $this->installedModule['version'],
        ];
    }
}
