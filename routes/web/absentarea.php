<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Absentarea\HomeController;

Route::domain('{absentarea}')->group(function () {
    Route::name('absentarea.')
         ->middleware(['web'])
         ->prefix(route_prefix('absentarea'))->group(function () {

            // Homepage Routes
             Route::get('absent')->name('home')->uses([HomeController::class, 'index']);
         });
});
