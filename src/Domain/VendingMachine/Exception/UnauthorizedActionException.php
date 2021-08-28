<?php

namespace App\Domain\VendingMachine\Exception;

use Exception;

class UnauthorizedActionException extends Exception
{
    protected $message = 'UNAUTHORIZED_ACTION';
}
