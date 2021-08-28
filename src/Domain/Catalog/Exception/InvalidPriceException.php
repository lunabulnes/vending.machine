<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidPriceException extends Exception
{
    protected $message = 'INVALID_PRICE';
}
