<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class StopMaintenance
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(): void
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->stopMaintenance();
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
