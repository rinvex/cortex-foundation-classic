<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

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
