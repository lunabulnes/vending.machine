<?php

namespace App\Application\Command\Exception;

use Exception;

class InvalidCommandException extends Exception
{
    protected $message = "INVALID_COMMAND_EXCEPTION";
}
