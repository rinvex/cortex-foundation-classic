<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Support\AggregateServiceProvider as BaseAggregateServiceProvider;

class ConsoleSupportServiceProvider extends BaseAggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
