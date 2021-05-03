<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Session;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * {@inheritdoc}
     */
    protected function addUserInformation(&$payload)
    {
        if ($this->container->has(Guard::class)) {
            $payload['user_id'] = $this->userId();
            $payload['user_type'] = ($user = $this->container->make(Guard::class)->user()) ? $user->getMorphClass() : null;
        }

        return $this;
    }
}
