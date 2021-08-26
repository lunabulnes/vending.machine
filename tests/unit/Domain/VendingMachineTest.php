<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
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
        $this->assertEquals(0.25, $vendingMachine->getAvailableMoney());
    }

    public function testCashIsAccumulated()
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(0.25));
        $vendingMachine->addUserCoin(Coin::create(1));
        $this->assertEquals(1.25, $vendingMachine->getAvailableMoney());
    }

    public function testReturnsUserCoins()
    {
        $coins = [Coin::create(0.25), Coin::create(1)];

        $vendingMachine = VendingMachine::create();
        foreach ($coins as $coin) {
            $vendingMachine->addUserCoin($coin);
        }

        $returnedCoins = $vendingMachine->returnUserCoins();
        $this->assertEquals($coins, $returnedCoins);
        $this->assertEquals(0, $vendingMachine->getAvailableMoney());
    }

    public function testUserCanBuyProductThatExistInTheCatalog()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 1, 10));

        $vendingMachine = VendingMachine::createWithCatalog($catalog);
        $vendingMachine->addUserCoin(Coin::create(1));
        $purchase = $vendingMachine->buy($product);

        $this->assertEquals($product, $purchase);
    }

    public function testUserCanNotBuyProductThatDoesNotExistInTheCatalog()
    {
        $product = Product::create('Juice');
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(1));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfQuantityIsZero()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 1, 0));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);
        $vendingMachine->addUserCoin(Coin::create(1));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfThereIsNotEnoughMoney()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 1, 10));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $this->expectException(NotEnoughMoneyException::class);
        $vendingMachine->buy($product);
    }
}
