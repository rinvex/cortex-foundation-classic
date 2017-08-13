<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {
    Route::name('backend.')
         ->namespace('Cortex\Foundation\Http\Controllers\Backend')
         ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
         ->prefix(config('rinvex.cortex.route.locale_prefix') ? '{locale}/backend' : 'backend')->group(function () {

         // Dashboard route
             Route::get('/')->name('home')->uses('HomeController@home');
         });
});
