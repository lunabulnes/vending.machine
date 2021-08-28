<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class StartMaintenance
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute()
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->startMaintenance();
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
