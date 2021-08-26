<?php

use App\Domain\Money\Coin;
use App\Domain\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function testVendingMachineCanBeCreated()
    {
        $vendingMachine = VendingMachine::create();
        $this->assertTrue($vendingMachine instanceof VendingMachine);
    }

    public function testUserCoinCanBeAdded()
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(0.25));
        $this->assertEquals(0.25, $vendingMachine->getUserMoney());
    }

    public function testMoneyIsAccumulated()
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(0.25));
        $vendingMachine->addUserCoin(Coin::create(1));
        $this->assertEquals(1.25, $vendingMachine->getUserMoney());
    }

    public function testReturnsUserMoney()
    {
        $coins = [Coin::create(0.25), Coin::create(1)];

        $vendingMachine = VendingMachine::create();
        foreach ($coins as $coin) {
            $vendingMachine->addUserCoin($coin);
        }

        $returnedCoins = $vendingMachine->returnUserMoney();
        $this->assertEquals($coins, $returnedCoins);
        $this->assertEquals(0, $vendingMachine->getUserMoney());
    }
}
