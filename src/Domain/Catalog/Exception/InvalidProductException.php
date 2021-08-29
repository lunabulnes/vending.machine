<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidProductException extends Exception
{
    /** @var string $message */
    protected $message = 'INVALID_PRODUCT';
}
