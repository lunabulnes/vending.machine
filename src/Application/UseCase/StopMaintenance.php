<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class StopMaintenance
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute()
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->stopMaintenance();
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
