<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Foundation\Mix;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class Application extends BaseApplication
{
    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
        $this->singleton(Mix::class);

        $this->singleton(BasePackageManifest::class, function () {
            return new PackageManifest(
                new Filesystem(),
                $this->basePath(),
                $this->getCachedPackagesPath()
            );
        });
    }
}
