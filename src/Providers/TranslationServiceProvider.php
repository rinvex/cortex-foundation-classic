<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Cortex\Foundation\Loaders\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], [__DIR__.'/lang', $app['path.lang']]);
        });
    }
}
