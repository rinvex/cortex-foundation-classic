<?php

declare(strict_types=1);

Route::domain('{subdomain}.'.domain())->group(function () {
    Route::name('tenantarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Foundation\Http\Controllers\Tenantarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.frontarea') : config('cortex.foundation.route.prefix.frontarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
