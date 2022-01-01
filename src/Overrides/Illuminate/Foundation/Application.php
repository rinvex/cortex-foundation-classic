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
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param int  $type  The type of the request
     *                    (one of HttpKernelInterface::MAIN_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool $catch Whether to catch exceptions or not
     *
     * @throws \Exception When an Exception occurs during processing
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(SymfonyRequest $request, int $type = self::MASTER_REQUEST, bool $catch = true)
    {
        return $this[HttpKernelContract::class]->handle(Request::createFromBase($request));
    }
}
