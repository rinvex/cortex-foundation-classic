<?php

declare(strict_types=1);

namespace Cortex\Foundation\Events;

use Cortex\Foundation\Models\Accessarea;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccessareaCreated implements ShouldBroadcast
{
    use InteractsWithSockets;
    use SerializesModels;
    use Dispatchable;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Cortex\Foundation\Models\Accessarea
     */
    public Accessarea $model;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     */
    public function __construct(Accessarea $accessarea)
    {
        $this->model = $accessarea;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('cortex.foundation.accessareas.index'),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'accessarea.created';
    }
}
