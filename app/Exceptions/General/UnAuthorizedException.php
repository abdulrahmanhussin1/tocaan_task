<?php

namespace App\Exceptions\General;

use Exception;

class UnAuthorizedException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Unauthorized access')
    {
        parent::__construct($message);
    }
}
