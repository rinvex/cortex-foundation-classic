<?php

declare(strict_types=1);

Route::domain('{routeDomain}')->group(function () {
    Route::name('frontarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Foundation\Http\Controllers\Frontarea')
         ->prefix(route_prefix('frontarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
             Route::post('country')->name('country')->uses('GenericController@country');
         });
});
