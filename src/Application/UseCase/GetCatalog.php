<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class GetCatalog
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute()
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        return $vendingMachine->getCatalog();
    }

}
