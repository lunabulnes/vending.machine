<?php

namespace App\Application\UseCase;

use App\Domain\VendingMachine\VendingMachineRepository;

class ReturnCoins
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute()
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $coins = $vendingMachine->returnUserCoins();
        $this->vendingMachineRepository->save($vendingMachine);

        return $coins;
    }
}
