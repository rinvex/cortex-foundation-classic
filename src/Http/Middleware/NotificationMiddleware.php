<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Krucas\Notification\Middleware\NotificationMiddleware as BaseNotificationMiddleware;

class NotificationMiddleware extends BaseNotificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
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
                $this->notification->container(null)->add($type, $message, false);
            }
        }

        $this->session->forget($this->key);

        return $next($request);
    }
}
