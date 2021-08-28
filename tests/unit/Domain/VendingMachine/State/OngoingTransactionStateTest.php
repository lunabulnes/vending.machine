<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Purchase;
use App\Domain\VendingMachine\State\OngoingTransactionState;
use PHPUnit\Framework\TestCase;

class OngoingTransactionStateTest extends TestCase
{
    public function testReadyVendingMachineCanBeCreated()
    {
        $vendingMachine = OngoingTransactionState::create();
        $this->assertTrue($vendingMachine instanceof OngoingTransactionState);
    }

    public function testUserCoinCanBeAdded()
    {
        $vendingMachine = OngoingTransactionState::create();
        $vendingMachine->addUserCoin(Coin::create(25));
        $this->assertEquals(25, $vendingMachine->getUserMoney());
    }

    public function testMoneyIsAccumulated()
    {
        $vendingMachine = OngoingTransactionState::create();
        $vendingMachine->addUserCoin(Coin::create(25));
        $vendingMachine->addUserCoin(Coin::create(100));
        $this->assertEquals(125, $vendingMachine->getUserMoney());
    }

    public function testReturnsUserCoins()
    {
        $coins = [Coin::create(25), Coin::create(100)];

        $vendingMachine = OngoingTransactionState::create();
        foreach ($coins as $coin) {
            $vendingMachine->addUserCoin($coin);
        }

        $returnedCoins = $vendingMachine->returnUserCoins();
        $this->assertEquals($coins, $returnedCoins);
        $this->assertEquals(0, $vendingMachine->getUserMoney());
    }
}
