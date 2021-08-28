<?php

use App\Domain\Money\Coin;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\Exception\UnauthorizedActionException;
use App\Domain\VendingMachine\State\ReadyVendingMachineState;
use App\Domain\VendingMachine\State\UnderMaintenanceVendingMachineState;
use App\Domain\VendingMachine\VendingMachineContext;
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
}
