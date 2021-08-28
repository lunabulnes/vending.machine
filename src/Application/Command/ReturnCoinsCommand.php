<?php

namespace App\Application\Command;

use App\Domain\VendingMachine\VendingMachineRepository;

class ReturnCoinsCommand implements Command
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(array $args)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $coins = $vendingMachine->returnUserCoins();
        $this->vendingMachineRepository->save($vendingMachine);

        return json_encode($coins);
    }
}
