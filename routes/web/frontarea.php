<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Controllers\Frontarea\HomeController;
use Cortex\Foundation\Http\Controllers\Frontarea\GenericController;
use Cortex\Foundation\Overrides\Livewire\Controllers\FileUploadHandler;
use Cortex\Foundation\Overrides\Livewire\Controllers\FilePreviewHandler;
use Cortex\Foundation\Overrides\Livewire\Controllers\HttpConnectionHandler;
use Cortex\Foundation\Overrides\Livewire\Controllers\LivewireJavaScriptAssets;

Route::domain('{frontarea}')->group(function () {
    Route::name('frontarea.')
         ->middleware(['web'])
         ->prefix(route_prefix('frontarea'))->group(function () {
             // Homepage Routes
             Route::get('/')->name('home')->uses([HomeController::class, 'index']);
             Route::post('country')->name('country')->uses([GenericController::class, 'country']);

             // Livewire Routes
             Route::get('livewire/turbo.js')->name('cortex.foundation.turbo.js')->uses([LivewireJavaScriptAssets::class, 'turbo'])->withoutMiddleware('web');
             Route::get('livewire/livewire.js')->name('cortex.foundation.livewire.js')->uses([LivewireJavaScriptAssets::class, 'source'])->withoutMiddleware('web');
             Route::get('livewire/livewire.js.map')->name('cortex.foundation.livewire.js.map')->uses([LivewireJavaScriptAssets::class, 'maps'])->withoutMiddleware('web');
             Route::post('livewire/message/{name}')->name('cortex.foundation.livewire.message')->uses([HttpConnectionHandler::class, '__invoke']);
             Route::get('livewire/preview-file/{filename}')->name('cortex.foundation.livewire.preview-file')->uses([FilePreviewHandler::class, 'handle']);
             Route::post('livewire/upload-file')->name('cortex.foundation.livewire.upload-file')->uses([FileUploadHandler::class, 'handle']);
         });
});
