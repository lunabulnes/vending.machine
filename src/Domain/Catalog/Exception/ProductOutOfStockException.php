<?php

namespace App\Domain\Catalog\Exception;

use Exception;

class ProductOutOfStockException extends Exception
{
    protected $message = 'PRODUCT_OUT_OF_STOCK';
}
