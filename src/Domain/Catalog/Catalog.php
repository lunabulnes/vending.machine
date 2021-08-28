<?php
declare(strict_types = 1);

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\ProductOutOfStockException;
use JsonSerializable;

class Catalog implements JsonSerializable
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
    public function decreaseStock(Product $product)
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

    public function jsonSerialize(): array
    {
        $temp = [];
        foreach ($this->stocks as $stock) {
            $temp[] = [
                'productName' => $stock->product()->name(),
                'price' => $stock->price(),
                'quantity' => $stock->quantity(),
            ];
        }
        return $temp;
    }
}
