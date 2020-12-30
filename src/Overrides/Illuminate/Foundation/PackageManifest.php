<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Rinvex\Composer\Services\ModuleManifest;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class PackageManifest extends BasePackageManifest
{
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
     * The installed packages.
     *
     * @var array
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
        $this->files = $files;
        $this->basePath = $basePath;
        $this->manifestPath = $manifestPath;
        $this->vendorPath = $basePath.'/vendor';
        $this->modulesManifestPath = app()->getCachedModulesPath();
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

        if (! is_file($this->manifestPath) || ! is_file($this->modulesManifestPath)) {
            $this->build();
        }

        return $this->manifest = is_file($this->manifestPath) ?
            $this->files->getRequire($this->manifestPath) : [];
    }

    /**
     * Write modules manifest.
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
     * Build the manifest and write it to disk.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return void
     */
    public function build()
    {
        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $this->installedPackages = $installed['packages'] ?? $installed;
        }

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $list = collect($this->installedPackages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter();

        $callback = function ($configuration, $package) {
            return in_array($package, config('rinvex.composer.core_modules'));
        };

        $disabledModules = collect($this->getModulesManifest())->reject(fn ($attributes, $module) => $attributes['autoload'])->keys();

        $this->write($list->filter($callback)->union($list->reject($callback))->except($disabledModules)->all());
    }

    /**
     * Write the given manifest array to disk.
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function writeModulesManifest(): void
    {
        $modulePath = app()->path().DIRECTORY_SEPARATOR;
        $installedPackages = collect($this->installedPackages);
        $paths = $this->files->glob(app()->path('*/*'), GLOB_ONLYDIR);
        $moduleManifest = new ModuleManifest($this->modulesManifestPath);

        collect($paths)->flatMap(function ($path) use ($modulePath, $moduleManifest, $installedPackages) {
            $module = Str::after($path, $modulePath);

            if ($installed = $installedPackages->firstWhere('name', $module)) {
                $moduleManifest->add($module, [
                    'active' => in_array($module, config('rinvex.composer.always_active')) ? true : false,
                    'autoload' => in_array($module, config('rinvex.composer.always_active')) ? true : false,
                    'version' => $installed['version'],
                ]);
            }
        });

        $moduleManifest->persist();
    }
}
