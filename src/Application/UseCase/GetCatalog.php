<?php

namespace App\Application\UseCase;

use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\VendingMachineRepository;

class GetCatalog
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(): Catalog
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        return $vendingMachine->getCatalog();
    }
}
