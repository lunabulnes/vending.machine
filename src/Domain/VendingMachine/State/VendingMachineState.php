<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\VendingMachine\VendingMachineContext;
use App\Domain\VendingMachine\VendingMachineInterface;

abstract class VendingMachineState implements VendingMachineInterface
{
    protected $context;

    public function setContext(VendingMachineContext $context)
    {
        $this->context = $context;
    }
}
