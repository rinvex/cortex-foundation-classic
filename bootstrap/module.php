<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Middleware\Clockwork;
use Illuminate\Database\Eloquent\Relations\Relation;

return function () {
    // Bind route models and constrains
    Route::pattern('locale', '[a-z]{2}');
    Route::pattern('accessarea', '[a-zA-Z0-9-_]+');
    Route::model('media', config('medialibrary.media_model'));
    Route::model('accessarea', config('cortex.foundation.models.accessarea'));

    // Map relations
    Relation::morphMap([
        'media' => config('medialibrary.media_model'),
        'accessarea' => config('cortex.foundation.models.accessarea'),
    ]);

    // Append middleware to the 'web' middleware group
    $this->app->environment('production') || Route::pushMiddlewareToGroup('web', Clockwork::class);
};
