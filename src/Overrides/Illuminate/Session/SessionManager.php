<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Session;

use Illuminate\Session\SessionManager as BaseSessionManager;

class SessionManager extends BaseSessionManager
{
    /**
     * Build the session instance.
     *
     * @param \SessionHandlerInterface $handler
     *
     * @return \Illuminate\Session\Store
     */
    protected function buildSession($handler)
    {
        return $this->config->get('session.encrypt')
                ? $this->buildEncryptedSession($handler)
                : new Store($this->config->get('session.cookie'), $handler);
    }

    /**
     * Build the encrypted session instance.
     *
     * @param \SessionHandlerInterface $handler
     *
     * @return \Cortex\Foundation\Overrides\Illuminate\Session\EncryptedStore
     */
    protected function buildEncryptedSession($handler)
    {
        return new EncryptedStore(
            $this->config->get('session.cookie'),
            $handler,
            $this->container['encrypter']
        );
    }
}
