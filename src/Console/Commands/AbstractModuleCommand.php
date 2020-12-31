<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

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
     * @param string $module
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return int
     */
    protected function process(string $module): int
    {
        $this->call('clear-compiled');

        $moduleManifest = new ModuleManifest($this->laravel->getCachedModulesPath());

        if ($attributes = $moduleManifest->load()->get($module)) {
            $attributes['active'] = true;
            $moduleManifest->add($module, $attributes, true);
        } else if ($this->isComposerModuleInstalled($module)) {
            $moduleManifest->add($module, $this->setComposerModuleAttributes());
        } else {
            $this->error('Module activation failed!');
            return 1;
        }

        $this->alert('Module activation succeeded!');
        $moduleManifest->persist();
        return 0;
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
     * @param array $attribute
     *
     * @return array
     */
    protected function getComposerModuleAttributes(string $module, array $attribute): array
    {
        return [
            'active' => in_array($module, config('rinvex.composer.always_active')) ? true : (isset($attribute['active']) ? (bool) $attribute['active'] : false),
            'autoload' => in_array($module, config('rinvex.composer.always_active')) ? true : (isset($attribute['autoload']) ? (bool) $attribute['autoload'] : false),
            'version' => $this->installedModule['version'],
        ];
    }

    /**
     * Set module attributes.
     *
     * @return array
     */
    abstract protected function setComposerModuleAttributes(): array;
}
