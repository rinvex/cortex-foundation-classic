<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Frontarea\HomeController;
use Cortex\Foundation\Http\Controllers\Frontarea\GenericController;

Route::domain('{frontarea}')->group(function () {
    Route::name('frontarea.')
         ->middleware(['web'])
         ->prefix(route_prefix('frontarea'))->group(function () {
             // Homepage Routes
             Route::get('/')->name('home')->uses([HomeController::class, 'index']);
             Route::post('country')->name('country')->uses([GenericController::class, 'country']);
         });
});
