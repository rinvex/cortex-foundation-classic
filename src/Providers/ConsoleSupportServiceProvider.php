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

namespace Cortex\Foundation\Providers;

use Illuminate\Database\SeedServiceProvider;
use Illuminate\Queue\ConsoleServiceProvider;
use Illuminate\Console\ScheduleServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Support\AggregateServiceProvider as BaseAggregateServiceProvider;

class ConsoleSupportServiceProvider extends BaseAggregateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        ScheduleServiceProvider::class,
        MigrationServiceProvider::class,
        SeedServiceProvider::class,
        ComposerServiceProvider::class,
        ConsoleServiceProvider::class,
    ];
}
