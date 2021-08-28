<?php

namespace App\Domain\Catalog;

class Stock
{
    private const INCREMENT = 1;

    private $product;
    private $price;
    private $quantity;

    public function __construct(Product $product, int $price, int $quantity)
    {
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function decreaseQuantity(): void
    {
        $this->quantity -= self::INCREMENT;
    }
}
