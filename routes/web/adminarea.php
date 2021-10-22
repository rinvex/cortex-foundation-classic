<?php

declare(strict_types=1);

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->namespace('Cortex\Foundation\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(route_prefix('adminarea'))->group(function () {

            // Adminarea Home route
             Route::get('/')->name('home')->uses('HomeController@index');

             // Accessareas Routes
             Route::name('cortex.foundation.accessareas.')->prefix('accessareas')->group(function () {
                 Route::match(['get', 'post'], '/')->name('index')->uses('AccessareasController@index');
                 Route::get('import')->name('import')->uses('AccessareasController@import');
                 Route::post('import')->name('stash')->uses('AccessareasController@stash');
                 Route::post('hoard')->name('hoard')->uses('AccessareasController@hoard');
                 Route::get('import/logs')->name('import.logs')->uses('AccessareasController@importLogs');
                 Route::get('create')->name('create')->uses('AccessareasController@create');
                 Route::post('create')->name('store')->uses('AccessareasController@store');
                 Route::get('{accessarea}')->name('show')->uses('AccessareasController@show');
                 Route::get('{accessarea}/edit')->name('edit')->uses('AccessareasController@edit');
                 Route::put('{accessarea}/edit')->name('update')->uses('AccessareasController@update');
                 Route::match(['get', 'post'], '{accessarea}/logs')->name('logs')->uses('AccessareasController@logs');
                 Route::delete('{accessarea}')->name('destroy')->uses('AccessareasController@destroy');
             });
         });
});
