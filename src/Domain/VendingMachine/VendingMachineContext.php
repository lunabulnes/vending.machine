<?php

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Money\Coin;
use App\Domain\VendingMachine\State\ReadyVendingMachineState;
use App\Domain\VendingMachine\State\VendingMachineState;

class VendingMachineContext implements VendingMachineInterface
{
    private $vendingMachineState;

    private function __construct(VendingMachineState $vendingMachineState)
    {
        $this->vendingMachineState = $vendingMachineState;
        $this->vendingMachineState->setContext($this);
    }

    public static function create()
    {
        return new self(ReadyVendingMachineState::create());
    }

    public static function createWithState(VendingMachineState $vendingMachineState)
    {
        return new self($vendingMachineState);
    }

    public function updateState(VendingMachineState $newState): void
    {
        $this->vendingMachineState = $newState;
        $this->vendingMachineState->setContext($this);
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->vendingMachineState->addUserCoin($coin);
    }

    public function getUserMoney(): int
    {
        return $this->vendingMachineState->getUserMoney();
    }

    public function returnUserCoins(): array
    {
        return $this->vendingMachineState->returnUserCoins();
    }

    public function getMachineMoney(): int
    {
        return $this->vendingMachineState->getMachineMoney();
    }

    public function buy(Product $product): Purchase
    {
        return $this->vendingMachineState->buy($product);
    }

    public function refillCatalog(Catalog $catalog): void
    {
        $this->vendingMachineState->refillCatalog($catalog);
    }

    public function refillChange(array $coins): void
    {
        $this->vendingMachineState->refillChange($coins);
    }
}
