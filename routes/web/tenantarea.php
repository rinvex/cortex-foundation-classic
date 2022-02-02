<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Tenantarea\HomeController;

Route::domain('{tenantarea}')->group(function () {
    Route::name('tenantarea.')
         ->middleware(['web'])
         ->prefix(route_prefix('tenantarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses([HomeController::class, 'index']);
         });
});
