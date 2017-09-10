<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Lord\Laroute;

use Lord\Laroute\Console\Commands\LarouteGeneratorCommand;
use Cortex\Foundation\Overrides\Lord\Laroute\Routes\Collection;
use Lord\Laroute\LarouteServiceProvider as BaseLarouteServiceProvider;

class LarouteServiceProvider extends BaseLarouteServiceProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCommand()
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
