<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

use Illuminate\Support\Str;

trait FiresCustomModelEvent
{
    /**
     * Fire a custom model event for the given event.
     *
     * @param string $event
     * @param string $method
     *
     * @return mixed|null
     */
    protected function fireCustomModelEvent($event, $method)
    {
        if (! isset($this->dispatchesEvents[$event])) {
            return;
        }

        if ($event === 'deleted') {
            $modelArray = $this->setAttribute('resource', $resource = $this->getMorphClass())
                               ->setAttribute('collection', Str::plural($resource))
                               ->setAttribute('action', mb_substr($event, 0, -1))
                               ->setAttribute('route_key', $this->getRouteKey())
                               ->setAttribute('event', $event)
                               ->toArray();

            $result = static::$dispatcher->{$method}(new $this->dispatchesEvents[$event]($modelArray));
        } else {
            $result = static::$dispatcher->{$method}(new $this->dispatchesEvents[$event]($this, $event));
        }

        if (! is_null($result)) {
            return $result;
        }
    }
}
