<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\State\OngoingTransactionState;
use PHPUnit\Framework\TestCase;

class OngoingTransactionStateTest extends TestCase
{
    public function testReadyVendingMachineCanBeCreated()
    {
        $vendingMachine = OngoingTransactionState::create();
        $this->assertTrue($vendingMachine instanceof OngoingTransactionState);
    }

    public function testMoneyIsAccumulated()
    {
        $vendingMachine = OngoingTransactionState::create();
        $vendingMachine->addUserCoin(Coin::create(25));
        $vendingMachine->addUserCoin(Coin::create(100));
        $this->assertEquals(125, $vendingMachine->getUserMoney());
    }

    public function testUserCanNotBuyProductThatDoesNotExistInTheCatalog()
    {
        $product = Product::create('Juice');
        $vendingMachine = OngoingTransactionState::create();
        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfQuantityIsZero()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 0));
        $vendingMachine = OngoingTransactionState::createWithCatalog($catalog);

        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachine->buy($product);
    }

    public function testUserCanNotBuyProductIfThereIsNotEnoughMoney()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));
        $vendingMachine = OngoingTransactionState::createWithCatalog($catalog);

        $this->expectException(NotEnoughMoneyException::class);
        $vendingMachine->buy($product);
    }

    public function testMachineCanNotReturnChangeIfItDoesNotHaveTheProperCoins()
    {
        $product = Product::create('Water');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 65, 10));
        $machineMoney = Money::createFromCoins([Coin::create(100)]);
        $vendingMachine = OngoingTransactionState::createWithMoneyAndCatalog($machineMoney, $catalog);

        $vendingMachine->addUserCoin(Coin::create(100));

        $this->expectException(NotEnoughMoneyException::class);
        $vendingMachine->buy($product);
    }
}
