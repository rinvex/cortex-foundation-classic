<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;

class GenericException extends Exception
{
    /**
     * The exception inputs.
     *
     * @var array
     */
    protected $inputs;

    /**
     * The exception redirection.
     *
     * @var string
     */
    protected $redirection;

    /**
     * The HTTP status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Create a new authorization exception.
     *
     * @param string     $message
     * @param null       $redirection
     * @param array|null $inputs
     * @param int        $statusCode
     */
    public function __construct(string $message, $redirection = null, array $inputs = null, int $statusCode = 302)
    {
        parent::__construct($message);

        $this->inputs = $inputs;
        $this->statusCode = $statusCode;
        $this->redirection = $redirection;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
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

    /**
     * Gets the Exception inputs.
     *
     * @return array
     */
    final public function getInputs()
    {
        return $this->inputs;
    }
}
