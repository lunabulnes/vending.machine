<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\Exception\UnauthorizedActionException;
use App\Domain\VendingMachine\Purchase\Purchase;
use App\Domain\VendingMachine\State\OngoingTransactionState;
use App\Domain\VendingMachine\State\ReadyVendingMachineState;
use App\Domain\VendingMachine\State\UnderMaintenanceVendingMachineState;
use App\Domain\VendingMachine\Context\VendingMachineContext;
use PHPUnit\Framework\TestCase;

class VendingMachineContextTest extends TestCase
{
    public function testVendingMachineContextCanBeCreated()
    {
        $vendingMachineContext = VendingMachineContext::create();
        $this->assertTrue($vendingMachineContext instanceof VendingMachineContext);
    }

    public function testCurrentStateIsHandled()
    {
        $vendingMachineContext = VendingMachineContext::createWithState(ReadyVendingMachineState::create());
        $vendingMachineContext->addUserCoin(Coin::create(25));
        $this->assertEquals(25, $vendingMachineContext->getUserMoney());
    }

    public function testStateCanBeUpdated()
    {
        $vendingMachineContext = VendingMachineContext::createWithState(ReadyVendingMachineState::create());
        $vendingMachineContext->updateState(UnderMaintenanceVendingMachineState::create());

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachineContext->addUserCoin(Coin::create(25));
    }

    public function testCanTransitionFromReadyTotMaintenanceState()
    {
        $initialState = ReadyVendingMachineState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($initialState);
        $vendingMachineContext->startMaintenance();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachineContext->addUserCoin(Coin::create(25));
    }

    public function testCanTransitionFromMaintenanceToReady()
    {
        $initialState = UnderMaintenanceVendingMachineState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($initialState);
        $vendingMachineContext->stopMaintenance();

        $vendingMachineContext->addUserCoin(Coin::create(25));
        $this->assertEquals(25, $vendingMachineContext->getUserMoney());
    }

    public function testItThrowsExceptionTransitioningFromReadyToReady()
    {
        $initialState = ReadyVendingMachineState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($initialState);

        $this->expectException(UnauthorizedActionException::class);
        $vendingMachineContext->stopMaintenance();
    }

    public function testItThrowsExceptionTransitioningFromMaintenanceToMaintenance()
    {
        $initialState = UnderMaintenanceVendingMachineState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($initialState);

        $this->expectException(UnauthorizedActionException::class);
        $vendingMachineContext->startMaintenance();
    }

    public function testItCanTransitionFromReadyToOngoingTransaction()
    {
        $initialState = ReadyVendingMachineState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($initialState);

        $vendingMachineContext->addUserCoin(Coin::create(25));
        $this->assertEquals(25, $vendingMachineContext->getUserMoney());
    }

    public function testItCanTransitionFromOngoingTransactionToReadyBuyingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 10));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $vendingMachineContext->addUserCoin(Coin::create(100));
        $purchase = $vendingMachineContext->buy($product);

        $this->assertEquals(new Purchase($product), $purchase);
    }

    public function testItCanTransitionFromOngoingTransactionToReadyReturningCoins()
    {
        $coins = [Coin::create(25), Coin::create(100)];

        $vendingMachineState = OngoingTransactionState::create();
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        foreach ($coins as $coin) {
            $vendingMachineContext->addUserCoin($coin);
        }

        $returnedCoins = $vendingMachineContext->returnUserCoins();
        $this->assertEquals([Coin::create(100), Coin::create(25)], $returnedCoins);
        $this->assertEquals(0, $vendingMachineContext->getUserMoney());
    }

    public function testUserGetsProductAndChangeIfThereIsTooMuchMoney()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 10));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $vendingMachineContext->addUserCoin(Coin::create(100));
        $vendingMachineContext->addUserCoin(Coin::create(25));
        $purchase = $vendingMachineContext->buy($product);

        $this->assertEquals(new Purchase($product, Money::createFromCoins([Coin::create(25)])), $purchase);
    }

    public function testUserDoesNotHaveMoneyLeftAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 10));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $vendingMachineContext->addUserCoin(Coin::create(100));
        $vendingMachineContext->buy($product);

        $this->assertEquals(0, $vendingMachineContext->getUserMoney());
    }

    public function testProductQuantityIsUpdatedAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 1));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $vendingMachineContext->addUserCoin(Coin::create(100));
        $vendingMachineContext->buy($product);
        $vendingMachineContext->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachineContext->buy($product);
    }

    public function testMoneyIsAddedToTheMachineAfterPurchasingAProduct()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 1));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $this->assertEquals(0, $vendingMachineContext->getMachineMoney());
        $vendingMachineContext->addUserCoin(Coin::create(100));
        $vendingMachineContext->buy($product);

        $this->assertEquals(100, $vendingMachineContext->getMachineMoney());
    }

    public function testUserCanNotBuyProductIfQuantityIsZero()
    {
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->addStock(Stock::create($product, 100, 1));
        $vendingMachineState = OngoingTransactionState::createWithCatalog($catalog);
        $vendingMachineContext = VendingMachineContext::createWithState($vendingMachineState);

        $vendingMachineContext->addUserCoin(Coin::create(100));
        $vendingMachineContext->buy($product);
        $vendingMachineContext->addUserCoin(Coin::create(100));

        $this->expectException(ProductOutOfStockException::class);
        $vendingMachineContext->buy($product);
    }
}
