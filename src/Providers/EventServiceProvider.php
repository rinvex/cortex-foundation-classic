<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Cortex\Foundation\Overrides\Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Discover the events and listeners for the application.
     *
     * @return array
     */
    public function discoverEvents()
    {
        return collect($this->discoverEventsWithin())
                    ->reject(function ($directory) {
                        return ! is_dir($directory);
                    })
                    ->reduce(function ($discovered, $directory) {
                        return array_merge_recursive(
                            $discovered,
                            DiscoverEvents::within($directory, base_path())
                        );
                    }, []);
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return $this->app['files']->glob($this->app->path('*/*/src/Listeners'));
    }
}
