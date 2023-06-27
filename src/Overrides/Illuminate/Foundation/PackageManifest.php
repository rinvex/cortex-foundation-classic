<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Rinvex\Composer\Services\Config;
use Illuminate\Filesystem\Filesystem;
use Rinvex\Composer\Services\Manifest;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class PackageManifest extends BasePackageManifest
{
    /**
     * Modules path.
     *
     * @var string
     */
    public $modulesPath;

    /**
     * The modules manifest path.
     *
     * @var string|null
     */
    public $modulesManifestPath;

    /**
     * The loaded modules manifest array.
     *
     * @var array
     */
    public $modulesManifest;

    /**
     * Extensions path.
     *
     * @var string
     */
    public $extensionsPath;

    /**
     * The extensions manifest path.
     *
     * @var string|null
     */
    public $extensionsManifestPath;

    /**
     * The loaded extensions manifest array.
     *
     * @var array
     */
    public $extensionsManifest;

    /**
     * The installed packages.
     *
     * @var \Illuminate\Support\Collection
     */
    public $installedPackages = [];

    /**
     * Create a new package manifest instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string                            $basePath
     * @param string                            $manifestPath
     *
     * @return void
     */
    public function __construct(Filesystem $files, $basePath, $manifestPath)
    {
        parent::__construct($files, $basePath, $manifestPath);

        // Using \Rinvex\Composer\Services\Config::get() instead of config() helper,
        // to avoid "Class 'config' not found" error when running composer commands, since
        // package service providers are not loaded yet, and `mergeConfigFrom` is not called yet.
        $this->modulesPath = Config::get('cortex-module.path').'/';
        $this->modulesManifestPath = Config::get('cortex-module.manifest');

        $this->extensionsPath = Config::get('cortex-extension.path').'/';
        $this->extensionsManifestPath = Config::get('cortex-extension.manifest');
    }

    /**
     * Get the current package manifest.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return array
     */
    protected function getManifest()
    {
        if (! is_null($this->manifest)) {
            return $this->manifest;
        }

        if (! is_file($this->manifestPath) || ! is_file($this->modulesManifestPath) || ! is_file($this->extensionsManifestPath)) {
            $this->build();
        }

        return $this->manifest = is_file($this->manifestPath) ?
            $this->files->getRequire($this->manifestPath) : [];
    }

    /**
     * Build the manifest and write it to disk.
     *
     * @throws \Exception
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return void
     */
    public function build()
    {
        $this->installedPackages = $this->getInstalledPackages();

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $list = $this->installedPackages->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()
          ->partition(fn ($item, $key) => Str::startsWith($key, config('app.provider_loading.priority_5')))->flatMap(fn ($values) => $values)
          ->partition(fn ($item, $key) => Str::startsWith($key, config('app.provider_loading.priority_4')))->flatMap(fn ($values) => $values)
          ->partition(fn ($item, $key) => Str::startsWith($key, config('app.provider_loading.priority_3')))->flatMap(fn ($values) => $values)
          ->partition(fn ($item, $key) => Str::startsWith($key, config('app.provider_loading.priority_2')))->flatMap(fn ($values) => $values)
          ->partition(fn ($item, $key) => Str::startsWith($key, config('app.provider_loading.priority_1')))->flatMap(fn ($values) => $values);

        $disabledModules = collect($this->getModulesManifest())->reject(fn ($attributes, $module) => $attributes['autoload'])->keys();
        $disabledExtensions = collect($this->getExtensionsManifest())->reject(fn ($attributes, $extension) => $attributes['autoload'])->keys();

        $this->write($list->except($disabledModules)->except($disabledExtensions)->all());
    }

    /**
     * Get module manifest.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     *
     * @return array
     */
    protected function getModulesManifest(): array
    {
        if (! is_null($this->modulesManifest)) {
            return $this->modulesManifest;
        }

        $this->modulesManifest = is_file($this->modulesManifestPath) ?
            $this->files->getRequire($this->modulesManifestPath) : [];

        if (! is_file($this->modulesManifestPath) || empty($this->modulesManifest)) {
            $this->writeModulesManifest();
        }

        return $this->modulesManifest;
    }

    /**
     * Write extension manifest.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     *
     * @return array
     */
    protected function getExtensionsManifest(): array
    {
        if (! is_null($this->extensionsManifest)) {
            return $this->extensionsManifest;
        }

        $this->extensionsManifest = is_file($this->extensionsManifestPath) ?
            $this->files->getRequire($this->extensionsManifestPath) : [];

        if (! is_file($this->extensionsManifestPath) || empty($this->extensionsManifest)) {
            $this->writeExtensionsManifest();
        }

        return $this->extensionsManifest;
    }

    /**
     * Write modules manifest array to disk.
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function writeModulesManifest(): void
    {
        $modulesManifest = new Manifest($this->modulesManifestPath);

        collect($this->getModulePaths())->flatMap(function ($path) use ($modulesManifest) {
            $module = Str::after($path, $this->modulesPath);

            if ($installed = $this->installedPackages->firstWhere('name', $module)) {
                $modulesManifest->add($module, [
                    'active' => in_array($module, Config::get('cortex-module.always_active')),
                    'autoload' => in_array($module, Config::get('cortex-module.always_active')),
                    'version' => $installed['version'],
                ]);
            }
        });

        $modulesManifest->persist();
    }


    /**
     * Write extensions manifest array to disk.
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function writeExtensionsManifest(): void
    {
        $extensionsManifest = new Manifest($this->extensionsManifestPath);

        collect($this->getExtensionPaths())->flatMap(function ($path) use ($extensionsManifest) {
            $extension = Str::after($path, $this->extensionsPath);

            if ($installed = $this->installedPackages->firstWhere('name', $extension)) {
                $extendedModule = $installed['extra']['cortex']['extends'];

                $extensionsManifest->add($extension, [
                    'active' => $this->modulesManifest[$extendedModule]['active'] && in_array($extension, Config::get('cortex-extension.always_active')),
                    'autoload' => $this->modulesManifest[$extendedModule]['active'] && in_array($extension, Config::get('cortex-extension.always_active')),
                    'version' => $installed['version'],
                    'extends' => $extendedModule ?? null,
                ]);
            }
        });

        $extensionsManifest->persist();
    }

    /**
     * Get module paths.
     *
     * @return array
     */
    public function getModulePaths(): array
    {
        return $this->files->glob($this->modulesPath.'*/*', GLOB_ONLYDIR);
    }

    /**
     * Get modules.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModuleNames(): Collection
    {
        return collect($this->getModulePaths())->map(fn ($path) => Str::after($path, $this->modulesPath));
    }

    /**
     * Get extension paths.
     *
     * @return array
     */
    public function getExtensionPaths(): array
    {
        return $this->files->glob($this->extensionsPath.'*/*', GLOB_ONLYDIR);
    }

    /**
     * Get extension names.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getExtensionNames(): Collection
    {
        return collect($this->getExtensionPaths())->map(fn ($path) => Str::after($path, $this->extensionsPath));
    }

    /**
     * Get installed composer packages.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return \Illuminate\Support\Collection
     */
    public function getInstalledPackages(): Collection
    {
        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode($this->files->get($path), true);
        }

        return collect($packages['packages'] ?? $packages ?? []);
    }
}
