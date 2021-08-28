<?php

namespace App\Domain\Money\Exception;

use Exception;

class NotEnoughCoinsException extends Exception
{
    protected $message = 'NOT_ENOUGH_COINS';
}
