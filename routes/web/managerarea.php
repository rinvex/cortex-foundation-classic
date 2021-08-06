<?php

declare(strict_types=1);

Route::domain('{subdomain}.'.domain())->group(function () {
    Route::name('managerarea.')
         ->namespace('Cortex\Foundation\Http\Controllers\Managerarea')
         ->middleware(['web', 'nohttpcache', 'can:access-managerarea'])
         ->prefix(route_prefix('managerarea'))->group(function () {

            // Managerarea Home route
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
