<?php

namespace App\Application\Command;

use App\Domain\VendingMachine\VendingMachineRepository;

class CheckUserTotalCommand implements Command
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(array $args)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        return $vendingMachine->getUserMoney();
    }
}
