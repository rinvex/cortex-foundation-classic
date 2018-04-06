<?php

declare(strict_types=1);

Route::domain('{subdomain}.'.domain())->group(function () {
    Route::name('managerarea.')
         ->namespace('Cortex\Foundation\Http\Controllers\Managerarea')
         ->middleware(['web', 'nohttpcache', 'can:access-managerarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.managerarea') : config('cortex.foundation.route.prefix.managerarea'))->group(function () {

            // Managerarea Home route
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
