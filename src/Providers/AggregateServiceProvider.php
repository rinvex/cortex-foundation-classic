<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Rinvex\Modulable\ModulableServiceProvider;
use Rinvex\Attributable\Providers\AttributableServiceProvider;
use Illuminate\Support\AggregateServiceProvider as BaseAggregateServiceProvider;

class AggregateServiceProvider extends BaseAggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ModulableServiceProvider::class,
        FoundationServiceProvider::class,
        AttributableServiceProvider::class,
        ConsoleSupportServiceProvider::class,
    ];
}
