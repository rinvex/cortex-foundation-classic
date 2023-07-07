<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Adminarea\HomeController;
use Cortex\Foundation\Http\Controllers\Adminarea\GenericController;
use Cortex\Foundation\Http\Controllers\Adminarea\AccessareasController;

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(route_prefix('adminarea'))->group(function () {
             // Adminarea Home route
             Route::get('/')->name('home')->uses([HomeController::class, 'index']);
             Route::post('country')->name('country')->uses([GenericController::class, 'country']);

             // Accessareas Routes
             Route::name('cortex.foundation.accessareas.')->prefix('accessareas')->group(function () {
                 Route::match(['get', 'post'], '/')->name('index')->uses([AccessareasController::class, 'index']);
                 Route::post('import')->name('import')->uses([AccessareasController::class, 'import']);
                 Route::get('create')->name('create')->uses([AccessareasController::class, 'create']);
                 Route::post('create')->name('store')->uses([AccessareasController::class, 'store']);
                 Route::get('{accessarea}')->name('show')->uses([AccessareasController::class, 'show']);
                 Route::get('{accessarea}/edit')->name('edit')->uses([AccessareasController::class, 'edit']);
                 Route::put('{accessarea}/edit')->name('update')->uses([AccessareasController::class, 'update']);
                 Route::match(['get', 'post'], '{accessarea}/logs')->name('logs')->uses([AccessareasController::class, 'logs']);
                 Route::delete('{accessarea}')->name('destroy')->uses([AccessareasController::class, 'destroy']);
             });
         });
});
