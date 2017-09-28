<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {
    Route::name('adminarea.')
         ->namespace('Cortex\Foundation\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

            // Dashboard route
             Route::get('/')->name('home')->uses('HomeController@index');
         });

    Route::name('memberarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Foundation\Http\Controllers\Memberarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.memberarea') : config('cortex.foundation.route.prefix.memberarea'))->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
