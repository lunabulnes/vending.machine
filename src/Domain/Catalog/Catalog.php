<?php

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\ProductOutOfStockException;
use JsonSerializable;

class Catalog implements JsonSerializable
{
    /** @var array<string, Stock> $stocks */
    private array $stocks;

    private function __construct()
    {
        $this->stocks = [];
    }

    public static function create(): Catalog
    {
        return new self();
    }

    public function addStock(Stock $stock): void
    {
        $this->stocks[$stock->product()->name()] = $stock;
    }

    /**
     * @throws ProductOutOfStockException
     */
    public function getPriceFor(Product $product): int
    {
        $stock = $this->getStockByProduct($product);
        return $stock->price();
    }

    /**
     * @throws ProductOutOfStockException
     */
    public function guardStockQuantity(Product $product): void
    {
        $stock = $this->getStockByProduct($product);
        if ($stock->quantity() === 0) {
            throw new ProductOutOfStockException();
        }
    }

    /**
     * @throws ProductOutOfStockException
     */
    public function decreaseStock(Product $product): void
    {
        $stock = $this->getStockByProduct($product);
        $stock->decreaseQuantity();
    }

    /**
     * @throws ProductOutOfStockException
     */
    private function getStockByProduct(Product $product): Stock
    {
        $this->guardProductExistsInCatalog($product);
        return $this->stocks[$product->name()];
    }

    /**
     * @throws ProductOutOfStockException
     */
    private function guardProductExistsInCatalog(Product $product): void
    {
        if (!isset($this->stocks[$product->name()])) {
            throw new ProductOutOfStockException();
        }
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        $temp = [];
        foreach ($this->stocks as $stock) {
            $temp[] = [
                'productName' => $stock->product()->name(),
                'price' => $stock->price() / 100,
                'quantity' => $stock->quantity(),
            ];
        }
        return $temp;
    }
}
