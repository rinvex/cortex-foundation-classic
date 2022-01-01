<?php

declare(strict_types=1);

Route::domain('{centralarea}')->group(function () {
    Route::name('centralarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Foundation\Http\Controllers\Centralarea')
         ->prefix(route_prefix('centralarea'))->group(function () {

            // Homepage Routes
             Route::get('central')->name('home')->uses('HomeController@index');
         });
});
