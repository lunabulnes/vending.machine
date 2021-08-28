<?php

use App\Domain\VendingMachine\State\ReadyVendingMachineState;
use PHPUnit\Framework\TestCase;

class ReadyVendingMachineStateTest extends TestCase
{
    public function testReadyVendingMachineCanBeCreated()
    {
        $vendingMachine = ReadyVendingMachineState::create();
        $this->assertTrue($vendingMachine instanceof ReadyVendingMachineState);
    }
}
