<?php

use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testMoneyCanBeCreatedWithoutCoins()
    {
        $money =  Money::createFromCoins();
        $this->assertTrue($money instanceof Money);
    }

    public function testMoneyCanBeCreatedWithCoins()
    {
        $coins = [Coin::create(100), Coin::create(100), Coin::create(5)];
        $money =  Money::createFromCoins($coins);
        $this->assertTrue($money instanceof Money);
        $total = $money->getTotalMoney();

        $this->assertEquals(205, $total);
    }

    public function testItReturnsExistingCoins()
    {
        $coins = [Coin::create(100), Coin::create(100), Coin::create(5)];
        $money =  Money::createFromCoins($coins);

        $returnedCoins = $money->returnCoins();
        $total = $money->getTotalMoney();

        $this->assertEquals($coins, $returnedCoins);
        $this->assertEquals(0, $total);
    }

    public function testThrowsExceptionIfDoesNotHaveEnoughMoney()
    {
        $money =  Money::createFromCoins([Coin::create(5)]);

        $this->expectException(NotEnoughMoneyException::class);
        $money->guardHasEnoughMoney(100);
    }

    public function testMoneyCanBeAdded()
    {
        $coinsA = [Coin::create(100), Coin::create(100), Coin::create(25)];
        $coinsB = [Coin::create(100), Coin::create(10), Coin::create(5)];

        $expectedCoins = [
            Coin::create(100),
            Coin::create(100),
            Coin::create(100),
            Coin::create(25),
            Coin::create(10),
            Coin::create(5)
        ];

        $moneyA =  Money::createFromCoins($coinsA);
        $moneyB = Money::createFromCoins($coinsB);

        $moneyA->add($moneyB);
        $total = $moneyA->getTotalMoney();

        $this->assertEquals(340, $total);

        $addedCoins = $moneyA->returnCoins();
        $this->assertEquals($expectedCoins, $addedCoins);
    }

    public function testMoneyCanBeSubtracted()
    {
        $coinsA = [Coin::create(100), Coin::create(100), Coin::create(25), Coin::create(5)];
        $coinsB = [Coin::create(100), Coin::create(5)];

        $expectedCoins = [
            Coin::create(100),
            Coin::create(25)
        ];

        $moneyA =  Money::createFromCoins($coinsA);
        $moneyB = Money::createFromCoins($coinsB);

        $moneyA->subtract($moneyB);
        $total = $moneyA->getTotalMoney();

        $this->assertEquals(125, $total);

        $addedCoins = $moneyA->returnCoins();
        $this->assertEquals($expectedCoins, $addedCoins);
    }

    public function testMoneyCanReturnChange()
    {
        $coins = [Coin::create(100), Coin::create(100), Coin::create(5)];
        $money =  Money::createFromCoins($coins);

        $change =  $money->getChange(100);
        $this->assertEquals(100, $change->getTotalMoney());
        $this->assertEquals([Coin::create(100)], $change->returnCoins());
    }

    public function testMoneyIsSubtractedWhenChangeIsReturned()
    {
        $coins = [Coin::create(100), Coin::create(5)];
        $money =  Money::createFromCoins($coins);

        $this->assertEquals(105, $money->getTotalMoney());
        $money->getChange(100);

        $this->assertEquals(5, $money->getTotalMoney());
    }

    public function testItThrowsExceptionIfChangeCanNotBeReturned()
    {
        $coins = [Coin::create(5)];
        $money =  Money::createFromCoins($coins);

        $this->expectException(NotEnoughMoneyException::class);
        $money->getChange(100);
    }
}
