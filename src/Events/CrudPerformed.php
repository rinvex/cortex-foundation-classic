<?php

declare(strict_types=1);

namespace Cortex\Foundation\Events;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CrudPerformed implements ShouldBroadcast
{
    use SerializesModels;
    use InteractsWithSockets;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * The event attributes.
     *
     * @var array
     */
    public array $attributes;

    /**
     * Update a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $event
     */
    public function __construct(Model $model, string $event)
    {
        $this->model = $model;
        $resource = Str::lower((new ReflectionClass($this->model))->getShortName());

        $this->attributes = [
            'event' => $event,
            'resource' => $resource,
            'action' => substr($event, 0, -1),
            'collection' => Str::plural($resource),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel("adminarea-{$this->attributes['collection']}-index"),
            new PrivateChannel("adminarea-{$this->attributes['collection']}-{$this->model->getRouteKey()}"),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'resource.'.$this->attributes['event'];
    }
}
