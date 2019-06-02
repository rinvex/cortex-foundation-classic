<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Lord\Laroute;

use Cortex\Foundation\Overrides\Lord\Laroute\Routes\Collection;
use Lord\Laroute\LarouteServiceProvider as BaseLarouteServiceProvider;
use Cortex\Foundation\Overrides\Lord\Laroute\Console\Commans\LarouteGeneratorCommand;

class LarouteServiceProvider extends BaseLarouteServiceProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCommand(): void
    {
        $this->app->singleton(
            'command.laroute.generate',
            function ($app) {
                $config = $app['config'];
                $routes = new Collection($app['router']->getRoutes(), $config->get('laroute.filter', 'all'), $config->get('laroute.action_namespace', ''));
                $generator = $app->make('Lord\Laroute\Generators\GeneratorInterface');

                return new LarouteGeneratorCommand($config, $routes, $generator);
            }
        );

        $this->commands('command.laroute.generate');
    }
}
