<?php

namespace App\Application\UseCase;

use App\Domain\Money\Coin;
use App\Domain\VendingMachine\VendingMachineRepository;

class ReturnCoins
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return array<Coin>
     */
    public function execute(): array
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $coins = $vendingMachine->returnUserCoins();
        $this->vendingMachineRepository->save($vendingMachine);

        return $coins;
    }
}
