<?php

declare(strict_types=1);

Route::domain('{absentarea}')->group(function () {
    Route::name('absentarea.')
         ->middleware(['web'])
         ->namespace('Cortex\Foundation\Http\Controllers\Absentarea')
         ->prefix(route_prefix('absentarea'))->group(function () {

            // Homepage Routes
             Route::get('absent')->name('home')->uses('HomeController@index');
         });
});
