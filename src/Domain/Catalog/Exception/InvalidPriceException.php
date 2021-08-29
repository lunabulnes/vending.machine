<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidPriceException extends Exception
{
    /** @var string $message */
    protected $message = 'INVALID_PRICE';
}
