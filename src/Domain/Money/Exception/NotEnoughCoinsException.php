<?php

namespace App\Domain\Money\Exception;

use Exception;

class NotEnoughCoinsException extends Exception
{
    /** @var string $message */
    protected $message = 'NOT_ENOUGH_COINS';
}
