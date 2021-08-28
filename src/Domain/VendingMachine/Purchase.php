<?php

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Product;
use App\Domain\Money\Money;
use JsonSerializable;

class Purchase implements JsonSerializable
{
    private $product;
    private $change;

    public function __construct(Product $product, ?Money $change = null)
    {
        $this->product = $product;
        $this->change = $change;
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product->name(),
            'change' => $this->change->getCoins()
        ];
    }
}
