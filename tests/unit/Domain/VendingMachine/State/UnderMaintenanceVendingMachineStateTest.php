<?php

use App\Domain\Money\Coin;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\State\UnderMaintenanceVendingMachineState;
use PHPUnit\Framework\TestCase;

class UnderMaintenanceVendingMachineStateTest extends TestCase
{
    public function testUserCanNotAddCoinsToMachine()
    {
        $vendingMachine = UnderMaintenanceVendingMachineState::create();

        $this->expectException(MachineOutOfServiceException::class);
        $vendingMachine->addUserCoin(Coin::create(25));
    }

}
