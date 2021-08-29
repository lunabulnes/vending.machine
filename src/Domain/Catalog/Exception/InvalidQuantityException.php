<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidQuantityException extends Exception
{
    /** @var string $message */
    protected $message = 'INVALID_QUANTITY';
}
