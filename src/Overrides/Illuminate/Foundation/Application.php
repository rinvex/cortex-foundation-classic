<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Foundation\Mix;
use Cortex\Foundation\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\LogServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Cortex\Foundation\Providers\RoutingServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Application extends BaseApplication
{
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new LogServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));
    }

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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(SymfonyRequest $request, int $type = self::MAIN_REQUEST, bool $catch = true): SymfonyResponse
    {
        return $this[HttpKernelContract::class]->handle(Request::createFromBase($request));
    }
}
