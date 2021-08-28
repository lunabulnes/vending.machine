<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\VendingMachine\Context\VendingMachineContext;
use App\Domain\VendingMachine\VendingMachine;

abstract class VendingMachineState implements VendingMachine
{
    protected $context;

    public function setContext(VendingMachineContext $context)
    {
        $this->context = $context;
    }
}
