<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class InvalidProductException extends Exception
{
    protected $message = 'INVALID_PRODUCT';
}
