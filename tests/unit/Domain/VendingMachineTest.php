<?php

use App\Domain\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function testVendingMachineCanBeCreated()
    {
        $vendingMachine = VendingMachine::create();
        $this->assertTrue($vendingMachine instanceof VendingMachine);
    }
}
