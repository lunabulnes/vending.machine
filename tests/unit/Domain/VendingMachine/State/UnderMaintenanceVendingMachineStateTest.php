<?php

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\State\UnderMaintenanceVendingMachineState;
use PHPUnit\Framework\TestCase;

class UnderMaintenanceVendingMachineStateTest extends TestCase
{
    public function testUnderMaintenanceVendingMachineCanBeCreated()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();
        $this->assertTrue($vendingMachine instanceof UnderMaintenanceVendingMachineState);
    }

    public function testCatalogCanBeRefilled()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();
        $product = Product::create('Juice');
        $catalog = Catalog::create();
        $catalog->refillStock(new Stock($product, 100, 10));

        $vendingMachine->refillCatalog($catalog);
        $machineCatalog = $vendingMachine->catalog();

        $this->assertEquals($catalog, $machineCatalog);
    }

    public function testChangeCanBeRefilled()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();
        $coins = [Coin::create(100), Coin::create(25), Coin::create(100)];
        $vendingMachine->refillChange($coins);

        $this->assertEquals(225, $vendingMachine->getMachineMoney());

    }

    public function testUserCanNotAddCoinsToMachine()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachine->addUserCoin(Coin::create(25));
    }

    public function testUserCanNotCheckTotalMoney()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachine->getUserMoney();
    }

    public function testUserCanNotRecoverCoins()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachine->returnUserCoins();
    }

    public function testCanNotBuyProduct()
    {
        $product = Product::create('Water');
        $vendingMachine = UnderMaintenanceVendingMachineState::create();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachine->buy($product);
    }
}
