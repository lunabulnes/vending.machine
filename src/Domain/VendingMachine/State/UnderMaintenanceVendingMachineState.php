<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\Purchase;

class UnderMaintenanceVendingMachineState extends VendingMachineState
{
    private $catalog;
    private $machineMoney;

    private function __construct(Money $machineMoney){
        $this->machineMoney = $machineMoney;
    }

    public static function create()
    {
        return new self(Money::createFromCoins());
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

    public function getMachineMoney(): int
    {
        return $this->machineMoney->getTotalMoney();
    }

    /**
     * @throws MachineOutOfServiceException
     */
    public function buy(Product $product): Purchase
    {
        throw new MachineOutOfServiceException();
    }

    public function refillCatalog(Catalog $catalog): void
    {
       $this->catalog = $catalog;
    }

    public function refillChange(array $coins): void
    {
        $this->machineMoney = Money::createFromCoins($coins);
    }

    public function catalog(): Catalog
    {
        return $this->catalog;
    }
}
