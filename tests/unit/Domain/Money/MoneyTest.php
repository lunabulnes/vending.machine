<?php

use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testCanCreateMoneyFromFloat()
    {
        $money = Money::createFromTotal(140);
        $expectedMoney = Money::createFromCoins([
            Coin::create(100),
            Coin::create(25),
            Coin::create(10),
            Coin::create(5)
        ]);
        $this->assertEquals($expectedMoney, $money);
    }
}
