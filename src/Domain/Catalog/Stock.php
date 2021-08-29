<?php

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\InvalidPriceException;
use App\Domain\Catalog\Exception\InvalidQuantityException;

class Stock
{
    private const INCREMENT = 1;

    private Product $product;
    private int $price;
    private int $quantity;

    private function __construct(Product $product, int $price, int $quantity)
    {
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * @throws InvalidPriceException
     * @throws InvalidQuantityException
     */
    public static function create(Product $product, int $price, int $quantity): Stock
    {
        self::guardPrice($price);
        self::guardQuantity($quantity);
        return new self($product, $price, $quantity);
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

    /**
     * @throws InvalidPriceException
     */
    private static function guardPrice(int $price): void
    {
        if ($price <= 0 || $price % 5 !== 0) {
            throw new InvalidPriceException();
        }
    }

    /**
     * @throws InvalidQuantityException
     */
    private static function guardQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidQuantityException();
        }
    }
}
