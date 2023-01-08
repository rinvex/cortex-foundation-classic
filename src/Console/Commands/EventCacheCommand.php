<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Foundation\Providers\DiscoveryServiceProvider;
use Illuminate\Foundation\Console\EventCacheCommand as BaseEventCacheCommand;

#[AsCommand(name: 'event:cache')]
class EventCacheCommand extends BaseEventCacheCommand
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

            $events[get_class($provider)] = $providerEvents;
        }

        return $events;
    }
}
