<?php

namespace App\Infrastructure\Cli\Exception;

use Exception;

class InvalidCommandException extends Exception
{
    protected $message = "INVALID_COMMAND_EXCEPTION";
}
