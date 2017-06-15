<?php

namespace Cortex\Foundation\Overrides\Illuminate\Foundation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        $providers = $this->make(PackageManifest::class)->providers();

        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
                    ->load(array_merge($this->config['app.providers'], $providers));
    }
}
