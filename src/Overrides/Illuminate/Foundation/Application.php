<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Foundation\Mix;
use Cortex\Foundation\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Application extends BaseApplication
{
    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedModulesPath()
    {
        return $this->normalizeCachePath('APP_MODULES_CACHE', 'cache/modules.php');
    }

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

    /**
     * {@inheritdoc}
     */
    public function handle(SymfonyRequest $request, int $type = self::MASTER_REQUEST, bool $catch = true)
    {
        return $this[HttpKernelContract::class]->handle(Request::createFromBase($request));
    }
}
