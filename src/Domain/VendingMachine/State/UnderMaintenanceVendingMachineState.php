<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Exception\MachineOutOfServiceException;
use App\Domain\VendingMachine\Exception\UnauthorizedActionException;
use App\Domain\VendingMachine\Purchase\Purchase;
use JsonSerializable;

class UnderMaintenanceVendingMachineState extends VendingMachineState implements JsonSerializable
{
    private Catalog $catalog;
    private Money $machineMoney;

    private function __construct(Money $machineMoney, Catalog $catalog)
    {
        $this->machineMoney = $machineMoney;
        $this->catalog = $catalog;
    }

    public static function create(): UnderMaintenanceVendingMachineState
    {
        return new self(Money::createFromCoins(), Catalog::create());
    }

    public static function createWithMoneyAndCatalog(
        Money $machineMoney,
        Catalog $catalog,
        Money $userMoney = null
    ): UnderMaintenanceVendingMachineState {
        return new self($machineMoney, $catalog);
    }

    public function getCatalog(): Catalog
    {
        return $this->catalog;
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

    public function addStock(Stock $stock): void
    {
        $this->catalog->addStock($stock);
    }

    public function refillChange(Money $change): void
    {
        $this->machineMoney->add($change);
    }

    public function catalog(): Catalog
    {
        return $this->catalog;
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function startMaintenance(): void
    {
        throw new UnauthorizedActionException();
    }

    public function stopMaintenance(): void
    {
        $this->context->updateState(ReadyVendingMachineState::createWithMoneyAndCatalog(
            $this->machineMoney,
            $this->catalog
        ));
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'class' => self::class,
            'machineMoney' => $this->machineMoney,
            'catalog' => $this->catalog,
        ];
    }
}
