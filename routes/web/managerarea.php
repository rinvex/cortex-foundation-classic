<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Managerarea\HomeController;
use Cortex\Foundation\Http\Controllers\Managerarea\GenericController;

Route::domain('{managerarea}')->group(function () {
    Route::name('managerarea.')
         ->middleware(['web', 'nohttpcache', 'can:access-managerarea'])
         ->prefix(route_prefix('managerarea'))->group(function () {

            // Managerarea Home route
             Route::get('/')->name('home')->uses([HomeController::class, 'index']);
             Route::post('country')->name('country')->uses([GenericController::class, 'country']);
        });
});
