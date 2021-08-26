<?php

use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    public function testCoinCanBeCreatedWithValidValue()
    {
        $coin = Coin::create(0.10);
        $this->assertEquals(0.10, $coin->value());
    }

    public function testThrowsExceptionIfCoinIsNotValid()
    {
        $this->expectException(InvalidCoinException::class);
        Coin::create(2);
    }
}
