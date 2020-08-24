<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
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
     * Create a new package manifest instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $basePath
     * @param  string  $manifestPath
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

        if (! file_exists($this->manifestPath) || ! file_exists($this->modulesManifestPath)) {
            $this->build();
        }

        return $this->manifest = file_exists($this->manifestPath) ?
            $this->files->getRequire($this->manifestPath) : [];
    }

    /**
     * Build the manifest and write it to disk.
     *
     * @return void
     */
    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $packages = $installed['packages'] ?? $installed;
        }

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $list = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter();

        $callback = function ($configuration, $package) {
            return $package === 'cortex/foundation';
        };

        $disabledModules = collect($this->getModulesManifest())->reject(fn ($attributes, $module) => $attributes['autoload'])->keys();

        $this->write($list->filter($callback)->union($list->reject($callback))->except($disabledModules)->all());
    }

    /**
     * Write modules manifest.
     *
     * @throws \Exception
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return array
     */
    protected function getModulesManifest(): array
    {
        if (! is_null($this->modulesManifest)) {
            return $this->modulesManifest;
        }

        if (! file_exists($this->modulesManifestPath)) {
            $this->writeModulesManifest();
        }

        return $this->modulesManifest = file_exists($this->modulesManifestPath) ?
            $this->files->getRequire($this->modulesManifestPath) : [];
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
        $modulePaths = $this->files->glob(app()->path('*/*'), GLOB_ONLYDIR);

        $modulesManifest = collect($modulePaths)->flatMap(function ($path) {
            $module = Str::after($path, app()->path().DIRECTORY_SEPARATOR);
            return [$module => [
                'active' => in_array($module, ['cortex/foundation', 'cortex/auth']) ? true : false,
                'autoload' => in_array($module, ['cortex/foundation', 'cortex/auth']) ? true : false,
            ]];
        })->toArray();

        if (! is_writable($dirname = dirname($this->modulesManifestPath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $this->files->replace(
            $this->modulesManifestPath, '<?php return '.var_export($modulesManifest, true).';'
        );
    }
}
