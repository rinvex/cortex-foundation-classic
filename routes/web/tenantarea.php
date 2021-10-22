<?php

declare(strict_types=1);

Route::domain('{tenantarea}')->group(function () {
    Route::name('tenantarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Foundation\Http\Controllers\Tenantarea')
         ->prefix(route_prefix('tenantarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
