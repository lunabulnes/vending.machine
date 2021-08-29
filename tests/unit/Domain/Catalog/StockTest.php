<?php

use App\Domain\Catalog\Exception\InvalidPriceException;
use App\Domain\Catalog\Exception\InvalidQuantityException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
    private $product;

    public function setUp(): void
    {
        $this->product = Product::create('Water');
    }

    public function testStockCanBeCreatedWithValidValue()
    {
        $stock = Stock::create($this->product, 70, 2);
        $this->assertTrue($stock instanceof Stock);
    }

    public function testThrowsExceptionIfPriceEquals0()
    {
        $this->expectException(InvalidPriceException::class);
        Stock::create($this->product, 0, 4);
    }

    public function testThrowsExceptionIfPriceIsNegative()
    {
        $this->expectException(InvalidPriceException::class);
        Stock::create($this->product, -10, 4);
    }

    public function testThrowsExceptionIfPriceCanNotBeDividedBy5()
    {
        $this->expectException(InvalidPriceException::class);
        Stock::create($this->product, 77, 4);
    }

    public function testThrowsExceptionIfQuantityIsNegative()
    {
        $this->expectException(InvalidQuantityException::class);
        Stock::create($this->product, 65, -10);
    }
}
