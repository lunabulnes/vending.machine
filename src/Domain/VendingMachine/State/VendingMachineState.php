<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\VendingMachine\Context\VendingMachineContext;
use App\Domain\VendingMachine\VendingMachine;

abstract class VendingMachineState implements VendingMachine
{
    protected VendingMachineContext $context;

    public function setContext(VendingMachineContext $context): void
    {
        $this->context = $context;
    }
}
