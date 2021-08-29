<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class NotEnoughMoneyException extends Exception
{
    /** @var string $message */
    protected $message = 'NOT_ENOUGH_MONEY';
}
