<?php

namespace App\Domain\Catalog;

class Stock
{
    private $product;
    private $price;
    private $quantity;

    public function __construct(Product $product, float $price, int $quantity)
    {
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
