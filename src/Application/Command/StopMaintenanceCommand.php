<?php

namespace App\Application\Command;

use App\Domain\VendingMachine\VendingMachineRepository;

class StopMaintenanceCommand implements Command
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(array $args)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->stopMaintenance();
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
