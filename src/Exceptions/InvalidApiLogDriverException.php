<?php

namespace AWT\Http\Exceptions;

use Exception;
use Throwable;

class InvalidApiLogDriverException extends Exception
{
    public function __construct(
        $message = 'Invalid Api Log Driver',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
