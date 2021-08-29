<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class GetUserTotal
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(): int
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        return $vendingMachine->getUserMoney();
    }
}
