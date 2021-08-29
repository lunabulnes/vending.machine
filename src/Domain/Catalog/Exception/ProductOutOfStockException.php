<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class ProductOutOfStockException extends Exception
{
    /** @var string $message */
    protected $message = 'PRODUCT_OUT_OF_STOCK';
}
