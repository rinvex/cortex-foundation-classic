<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {
    Route::name('backend.')
         ->namespace('Cortex\Foundation\Http\Controllers\Backend')
         ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/backend' : 'backend')->group(function () {

            // Dashboard route
             Route::get('/')->name('home')->uses('HomeController@index');
         });

    Route::name('userarea.')
         ->middleware(['web', 'nohttpcache'])
         ->namespace('Cortex\Foundation\Http\Controllers\Userarea')
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/userarea' : 'userarea')->group(function () {

            // Homepage Routes
             Route::get('/')->name('home')->uses('HomeController@index');
         });
});
