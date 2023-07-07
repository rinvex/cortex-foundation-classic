<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire;

use Exception;
use ReflectionClass;
use Livewire\Component;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

use function Livewire\str;

class LivewireComponentsFinder
{
    protected $path;

    protected $files;

    protected $manifest;

    protected $manifestPath;

    public function __construct(Filesystem $files, $manifestPath, $path)
    {
        $this->files = $files;
        $this->path = $path;
        $this->manifestPath = $manifestPath;
    }

    public function find($alias)
    {
        $manifest = $this->getManifest();

        return $manifest[$alias] ?? $manifest["{$alias}.index"] ?? null;
    }

    public function getManifest()
    {
        if (! is_null($this->manifest)) {
            return $this->manifest;
        }

        if (! file_exists($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = $this->files->getRequire($this->manifestPath);
    }

    public function build()
    {
        $this->manifest = $this->getClassNames()
            ->mapWithKeys(function ($class) {
                return [$class::getName() => $class];
            })->toArray();

        $this->write($this->manifest);

        return $this;
    }

    protected function write(array $manifest)
    {
        if (! is_writable(dirname($this->manifestPath))) {
            throw new Exception('The '.dirname($this->manifestPath).' directory must be present and writable.');
        }

        $this->files->put($this->manifestPath, '<?php return '.var_export($manifest, true).';', true);
    }

    public function getClassNames()
    {
        return $this->getComponentFiles()
            ->map(function (SplFileInfo $file) {
                return ucwords(
                    str($file->getPathname())
                        ->after(app_path().'/')
                        ->replace(['src/', '/', '.php'], ['', '\\', ''])
                        ->__toString(),
                    '\\'
                );
            })
            ->filter(function (string $class) {
                return is_subclass_of($class, Component::class) &&
                    ! (new ReflectionClass($class))->isAbstract();
            });
    }

    public function getComponentFiles()
    {
        // @TODO: missing extensions support, we need to loop through both modules & extensions and register components
        $accessareaResources = app('accessareas')->map(fn ($accessarea) => 'src/Http/Components/'.ucfirst($accessarea->slug))->toArray();
        $moduleResources = $accessareaResources ? $this->files->moduleResources($accessareaResources, 'files', 4) : [];

        return collect($moduleResources)->prioritizeLoading();
    }
}
