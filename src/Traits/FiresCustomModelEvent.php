<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

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

        $result = static::$dispatcher->{$method}(new $this->dispatchesEvents[$event]($this, $event));

        if (! is_null($result)) {
            return $result;
        }
    }
}
