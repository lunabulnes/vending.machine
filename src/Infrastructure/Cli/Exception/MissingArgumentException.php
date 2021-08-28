<?php

namespace App\Infrastructure\Cli\Exception;

use Exception;

class MissingArgumentException extends Exception
{
    public function __construct(string $argumentName)
    {
        parent::__construct('MISSING_ARGUMENT: ' . strtoupper($argumentName));
    }
}
