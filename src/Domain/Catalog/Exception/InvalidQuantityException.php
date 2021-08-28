<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidQuantityException extends Exception
{
    protected $message = 'INVALID_QUANTITY';
}
