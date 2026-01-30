<?php

namespace App\Exceptions\General;

use Exception;

class NotFoundItemException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Item not found')
    {
        parent::__construct($message);
    }
}
