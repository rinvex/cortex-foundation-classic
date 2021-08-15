<?php

declare(strict_types=1);

Route::domain('{tenant_domain}')->group(function () {
    Route::name('tenantarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Foundation\Http\Controllers\Tenantarea')
         ->prefix(route_prefix('frontarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
