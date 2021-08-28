<?php

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Product;
use App\Domain\Money\Money;

class Purchase
{
    private $product;
    private $money;

    public function __construct(Product $product, ?Money $money = null)
    {
        $this->product = $product;
        $this->money = $money;
    }
}
