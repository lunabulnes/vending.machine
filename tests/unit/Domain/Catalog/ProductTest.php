<?php

use App\Domain\Catalog\Exception\InvalidProductException;
use App\Domain\Catalog\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCanBeCreatedWithValidValue()
    {
        $product = Product::create('Water');
        $this->assertEquals('Water', $product->name());
    }

    public function testThrowsExceptionIfCoinIsNotValid()
    {
        $this->expectException(InvalidProductException::class);
        Product::create('Cake');
    }
}
