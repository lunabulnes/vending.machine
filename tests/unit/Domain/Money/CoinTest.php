<?php

use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    public function testCoinCanBeCreatedWithValidValue()
    {
        $coin = Coin::create(100);
        $this->assertEquals(100, $coin->value());
    }

    public function testThrowsExceptionIfCoinIsNotValid()
    {
        $this->expectException(InvalidCoinException::class);
        Coin::create(2);
    }

    public function testCanCreate1UnitCoinFromFloat()
    {
        $coin = Coin::createBiggest(100);
        $this->assertEquals(Coin::create(100), $coin);
    }

    public function testCanCreate1UnitCoinFromFloatWithExcessMoney()
    {
        $coin = Coin::createBiggest(120);
        $this->assertEquals(Coin::create(100), $coin);
    }

    public function testCanCreate25CentsCoinFromFloat()
    {
        $coin = Coin::createBiggest(25);
        $this->assertEquals(Coin::create(25), $coin);
    }

    public function testCanCreate25CentsCoinFromFloatWithExcessMoney()
    {
        $coin = Coin::createBiggest(50);
        $this->assertEquals(Coin::create(25), $coin);
    }

    public function testCanCreate10CentsCoinFromFloat()
    {
        $coin = Coin::createBiggest(10);
        $this->assertEquals(Coin::create(10), $coin);
    }

    public function testCanCreate10CentsCoinFromFloatWithExcessMoney()
    {
        $coin = Coin::createBiggest(15);
        $this->assertEquals(Coin::create(10), $coin);
    }

    public function testCanCreate5CentsCoinFromFloat()
    {
        $coin = Coin::createBiggest(5);
        $this->assertEquals(Coin::create(5), $coin);
    }

    public function testCanCreate5CentsCoinFromFloatWithExcessMoney()
    {
        $coin = Coin::createBiggest(7);
        $this->assertEquals(Coin::create(5), $coin);
    }
}
