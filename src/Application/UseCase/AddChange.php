<?php

namespace App\Application\UseCase;

use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\VendingMachineRepository;

class AddChange
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @throws InvalidCoinException
     */
    public function execute(int $value, int $quantity)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $coins =$this->getCoins($value, $quantity);
        $vendingMachine->refillChange(Money::createFromCoins($coins));
        $this->vendingMachineRepository->save($vendingMachine);
    }

    /**
     * @throws InvalidCoinException
     */
    private function getCoins(int $value, int $quantity) {
        $coins = [];
        while ($quantity > 0) {
            $coins[] = Coin::create($value);
            $quantity -= 1;
        }
        return $coins;
    }
}
