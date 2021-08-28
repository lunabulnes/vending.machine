<?php
declare(strict_types = 1);

namespace App\Domain\VendingMachine\State;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Money\Money;
use App\Domain\Money\Coin;
use App\Domain\VendingMachine\Exception\UnauthorizedActionException;
use App\Domain\VendingMachine\Purchase\Purchase;

class ReadyVendingMachineState extends VendingMachineState
{
    private $userMoney;
    private $machineMoney;
    private $catalog;

    private function __construct(Money $userMoney, Money $machineMoney, Catalog $catalog)
    {
        $this->userMoney = $userMoney;
        $this->machineMoney = $machineMoney;
        $this->catalog = $catalog;
    }

    public static function create(): ReadyVendingMachineState
    {
        return new self(Money::createFromCoins(), Money::createFromCoins(), Catalog::create());
    }

    public static function createWithCatalog(Catalog $catalog): ReadyVendingMachineState
    {
        return new self(Money::createFromCoins(), Money::createFromCoins(), $catalog);
    }

    public static function createWithMoneyAndCatalog(
        Money $machineMoney,
        Catalog $catalog,
        Money $userMoney = null
    ): ReadyVendingMachineState {
        return new self($userMoney ?? Money::createFromCoins(), $machineMoney, $catalog);
    }

    public function getCatalog(): Catalog
    {
        return $this->catalog;
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->userMoney->addCoin($coin);
        $this->context->updateState(OngoingTransactionState::createWithMoneyAndCatalog(
            $this->machineMoney,
            $this->catalog,
            $this->userMoney
        ));
    }

    public function getUserMoney(): int
    {
        return $this->userMoney->getTotalMoney();
    }


    public function getMachineMoney(): int
    {
        return $this->machineMoney->getTotalMoney();
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function returnUserCoins(): array
    {
        throw new UnauthorizedActionException();
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function buy(Product $product): Purchase
    {
        throw new UnauthorizedActionException();
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function refillCatalog(Catalog $catalog): void
    {
        throw new UnauthorizedActionException();
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function refillChange(array $coins): void
    {
        throw new UnauthorizedActionException();
    }

    public function startMaintenance(): void
    {
        $this->context->updateState(UnderMaintenanceVendingMachineState::createWithMoneyAndCatalog(
            $this->machineMoney,
            $this->catalog
        ));
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function stopMaintenance(): void
    {
        throw new UnauthorizedActionException();
    }

    public function jsonSerialize(): array
    {
        return [
            'class' => self::class,
            'userMoney' => $this->userMoney,
            'machineMoney' => $this->machineMoney,
            'catalog' => $this->catalog,
        ];
    }
}
