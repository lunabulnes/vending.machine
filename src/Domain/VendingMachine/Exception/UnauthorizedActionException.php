<?php

namespace App\Domain\VendingMachine\Exception;

use Exception;

class UnauthorizedActionException extends Exception
{
    /** @var string $message */
    protected $message = 'UNAUTHORIZED_ACTION';
}
