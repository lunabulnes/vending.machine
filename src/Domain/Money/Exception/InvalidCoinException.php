<?php

namespace App\Domain\Money\Exception;

use Exception;

class InvalidCoinException extends Exception
{
    protected $message = 'INVALID_COIN';
}
