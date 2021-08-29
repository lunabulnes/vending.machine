<?php

namespace App\Domain\VendingMachine\Purchase;

use App\Domain\Catalog\Product;
use App\Domain\Money\Money;
use JsonSerializable;

class Purchase implements JsonSerializable
{
    private Product $product;
    private ?Money $change;

    public function __construct(Product $product, ?Money $change = null)
    {
        $this->product = $product;
        $this->change = $change;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return ['product' => $this->product->name()] + ($this->change ? ['change' => $this->change->getCoins()] : []);
    }
}
