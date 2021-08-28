<?php

namespace App\Application\Command;

use App\Domain\Money\Coin;
use App\Domain\VendingMachine\VendingMachineRepository;

class AddCoinCommand implements Command
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(array $args)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->addUserCoin(Coin::create($args[0]));
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
