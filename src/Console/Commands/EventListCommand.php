<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Cortex\Foundation\Providers\DiscoveryServiceProvider;
use Illuminate\Foundation\Console\EventListCommand as BaseEventListCommand;

class EventListCommand extends BaseEventListCommand
{
    /**
     * Get all of the events and listeners configured for the application.
     *
     * @return array
     */
    protected function getEvents()
    {
        $events = [];

        foreach ($this->laravel->getProviders(DiscoveryServiceProvider::class) as $provider) {
            $providerEvents = array_merge_recursive($provider->discoverEvents(), $provider->listens());

            $events = array_merge_recursive($events, $providerEvents);
        }

        if ($this->filteringByEvent()) {
            $events = $this->filterEvents($events);
        }

        return collect($events)->map(function ($listeners, $event) {
            return ['Event' => $event, 'Listeners' => implode(PHP_EOL, $listeners)];
        })->sortBy('Event')->values()->toArray();
    }
}
