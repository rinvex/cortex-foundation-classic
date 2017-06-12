<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;

class GenericException extends Exception
{
    /**
     * The exception redirection.
     *
     * @var string
     */
    protected $redirection;

    /**
     * Create a new authorization exception.
     *
     * @param string $message
     * @param array  $redirection
     */
    public function __construct($message = 'This action is unauthorized.', $redirection = null)
    {
        parent::__construct($message);

        $this->redirection = $redirection;
    }

    /**
     * Gets the Exception redirection.
     *
     * @return string
     */
    final public function getRedirection()
    {
        return $this->redirection;
    }
}
