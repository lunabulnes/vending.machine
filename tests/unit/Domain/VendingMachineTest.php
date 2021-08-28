<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Domain\Money\Exception\NotEnoughCoinsException;
use App\Domain\Money\Money;
use App\Domain\Purchase;
use App\Domain\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachinetest extends TestCase
{
    public function testVendingMachineCanBeCreated()
    {
        $vendingMachine = VendingMachine::create();
        $this->assertTrue($vendingMachine instanceof VendingMachine);
    }

    public function testUserCoinCanBeAdded()
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(25));
        $this->assertEquals(25, $vendingMachine->getUserMoney());
    }

    public function testMoneyIsAccumulated()
    {
        $vendingMachine = VendingMachine::create();
        $vendingMachine->addUserCoin(Coin::create(25));
        $vendingMachine->addUserCoin(Coin::create(100));
        $this->assertEquals(125, $vendingMachine->getUserMoney());
    }

    public function testReturnsUserCoins()
    {
        $coins = [Coin::create(25), Coin::create(100)];

        $vendingMachine = VendingMachine::create();
        foreach ($coins as $coin) {
            $vendingMachine->addUserCoin($coin);
        }

        $returnedCoins = $vendingMachine->returnUserCoins();
        $this->assertEquals($coins, $returnedCoins);
        $this->assertEquals(0, $vendingMachine->getUserMoney());
    }

    public function testUserCanBuyProductThatExistInTheCatalog()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));
        $purchase = $vendingMachine->buy($product);

        $this->assertEquals(new Purchase($product), $purchase);
    }

    public function testUserCanNotBuyProductThatDoesNotExistInTheCatalog()
    {
        $product = Product::create('Juice');
        $vendingMachine = VendingMachine::create();

        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfQuantityIsZero()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 0));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfThereIsNotEnoughMoney()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $this->expectException(NotEnoughMoneyException::class);
        $vendingMachine->buy($product);
    }

    public function testUserGetsProductAndChangeIfThereWasTooMuchMoney()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));
        $vendingMachine->addUserCoin(Coin::create(25));
        $purchase = $vendingMachine->buy($product);

        $this->assertEquals(new Purchase($product, Money::createFromCoins([Coin::create(25)])), $purchase);
    }

    public function testUserDoesNotHaveMoneyLeftAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));
        $vendingMachine->buy($product);

        $this->assertEquals(0, $vendingMachine->getUserMoney());
    }

    public function testProductQuantityIsUpdatedAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 1));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));
        $vendingMachine->buy($product);
        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testMoneyIsAddedToTheMachineAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 1));
        $vendingMachine = VendingMachine::createWithCatalog($catalog);

        $this->assertEquals(0, $vendingMachine->getMachineMoney());
        $vendingMachine->addUserCoin(Coin::create(100));
        $vendingMachine->buy($product);

        $this->assertEquals(100, $vendingMachine->getMachineMoney());
    }

    public function testMachineCanNotReturnChangeIfItDoesNotHaveTheProperCoins()
    {
        $product = Product::create('Water');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 65, 10));
        $machineMoney = Money::createFromCoins([Coin::create(100)]);
        $vendingMachine = VendingMachine::createWithMoneyAndCatalog($machineMoney, $catalog);

        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(NotEnoughMoneyException::class);
        $vendingMachine->buy($product);
    }
}
