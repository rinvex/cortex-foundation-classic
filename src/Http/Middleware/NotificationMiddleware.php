<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Illuminate\Support\ViewErrorBag;
use Krucas\Notification\Middleware\NotificationMiddleware as Middleware;

class NotificationMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $containers = $this->session->get($this->key, []);

        if (count($containers) > 0) {
            foreach ($containers as $name => $messages) {
                /** @var \Krucas\Notification\Message $message */
                foreach ($messages as $message) {
                    $this->notification->container($name)->add($message->getType(), $message, false);
                }
            }
        }

        foreach (config('notification.default_types') as $type) {
            if ($request->session()->has($type)) {
                $message = $request->session()->get($type);

                if ($message instanceof ViewErrorBag) {
                    foreach ($message->messages() as $key => $values) {
                        $message = $values[0];
                    }
                }

                $this->notification->container(null)->add($type, $message, false);
            }
        }

        $this->session->forget($this->key);

        return $next($request);
    }
}
