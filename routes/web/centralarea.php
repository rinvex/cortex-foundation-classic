<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Centralarea\HomeController;

Route::domain('{centralarea}')->group(function () {
    Route::name('centralarea.')
         ->middleware(['web'])
         ->prefix(route_prefix('centralarea'))->group(function () {

            // Homepage Routes
             Route::get('central')->name('home')->uses([HomeController::class, 'index']);
         });
});
