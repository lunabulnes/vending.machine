<?php

namespace App\Domain\Money\Exception;

use Exception;

class InvalidCoinException extends Exception
{
    /** @var string $message */
    protected $message = 'INVALID_COIN';
}
