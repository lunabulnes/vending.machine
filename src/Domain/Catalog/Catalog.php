<?php
declare(strict_types = 1);

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\ProductOutOfStockException;

class Catalog
{
    private $stocks;

    private function __construct()
    {
        $this->stocks = [];
    }

    public static function create(): Catalog
    {
        return new self();
    }

    public function refillStock(Stock $stock): void
    {
        $this->stocks[$stock->product()->name()] = $stock;
    }

    /**
     * @throws ProductOutOfStockException
     */
    public function getPriceFor(Product $product): float
    {
        $this->guardProductExistsInCatalog($product);
        return $this->stocks[$product->name()]->price();
    }

    /**
     * @throws ProductOutOfStockException
     */
    public function guardQuantity(Product $product): void
    {
        $this->guardProductExistsInCatalog($product);
        if ($this->stocks[$product->name()]->quantity() === 0) {
            throw new ProductOutOfStockException();
        }
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
}
