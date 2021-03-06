<?php

namespace App\Application\UseCase;

use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Domain\VendingMachine\VendingMachineRepository;

class AddCoin
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @throws InvalidCoinException
     */
    public function execute(int $value): void
    {
        $coin = Coin::create($value);
        $vendingMachine = $this->vendingMachineRepository->get();
        $vendingMachine->addUserCoin($coin);
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
