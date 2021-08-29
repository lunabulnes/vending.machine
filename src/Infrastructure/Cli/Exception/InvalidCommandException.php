<?php

namespace App\Infrastructure\Cli\Exception;

use Exception;

class InvalidCommandException extends Exception
{
    /** @var string $message */
    protected $message = "INVALID_COMMAND_EXCEPTION";
}
