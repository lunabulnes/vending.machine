<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\Catalog\Product;
use App\Domain\Money\Coin;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\Purchase;

class UnderMaintenanceVendingMachineState extends VendingMachineState
{
    private function __construct(){}

    public static function create()
    {
        return new self();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function addUserCoin(Coin $coin): void
    {
        throw new MachineOutOfServiceException();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function getUserMoney(): int
    {
        throw new MachineOutOfServiceException();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function returnUserCoins(): array
    {
        throw new MachineOutOfServiceException();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function getMachineMoney(): int
    {
        throw new MachineOutOfServiceException();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function buy(Product $product): Purchase
    {
        throw new MachineOutOfServiceException();
    }
}
