<?php

declare(strict_types=1);

use Cortex\Foundation\Http\Middleware\Clockwork;

return function () {
    // Bind route models and constrains
    Route::pattern('locale', '[a-z]{2}');
    Route::pattern('accessarea', '[a-zA-Z0-9-_]+');
    Route::model('media', config('medialibrary.media_model'));
    Route::model('accessarea', config('cortex.foundation.models.accessarea'));

    Route::pattern('central_domain', central_domains());
    Route::pattern('tenant_domain', tenant_domains());

    // Append middleware to the 'web' middleware group
    $this->app->environment('production') || Route::pushMiddlewareToGroup('web', Clockwork::class);
};
