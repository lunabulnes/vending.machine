<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class NotEnoughMoneyException extends Exception
{
    protected $message = 'NOT_ENOUGH_MONEY';
}
