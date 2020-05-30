<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('frontarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Foundation\Http\Controllers\Frontarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.frontarea') : config('cortex.foundation.route.prefix.frontarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
             Route::post('country')->name('country')->uses('GenericController@country');
         });
});
