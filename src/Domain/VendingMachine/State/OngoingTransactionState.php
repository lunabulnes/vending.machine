<?php

namespace App\Domain\VendingMachine\State;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Exception\UnauthorizedActionException;
use App\Domain\VendingMachine\Purchase;

class OngoingTransactionState extends VendingMachineState
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

    public static function create(): OngoingTransactionState
    {
        return new self(Money::createFromCoins(), Money::createFromCoins(), Catalog::create());
    }

    public static function createWithCatalog(Catalog $catalog): OngoingTransactionState
    {
        return new self(Money::createFromCoins(), Money::createFromCoins(), $catalog);
    }

    public static function createWithMoneyAndCatalog(
        Money $machineMoney,
        Catalog $catalog,
        Money $userMoney = null
    ): OngoingTransactionState {
        return new self($userMoney ?? Money::createFromCoins(), $machineMoney, $catalog);
    }

    /**
     * @throws UnauthorizedActionException
     */
    public function getCatalog(): Catalog
    {
        throw new UnauthorizedActionException();
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->userMoney->addCoin($coin);
    }

    public function getUserMoney(): int
    {
        return $this->userMoney->getTotalMoney();
    }

    public function returnUserCoins(): array
    {
        $coins = $this->userMoney->returnCoins();
        $this->context->updateState(ReadyVendingMachineState::createWithMoneyAndCatalog(
            $this->machineMoney,
            $this->catalog
        ));
        return $coins;
    }

    public function getMachineMoney(): int
    {
        return $this->machineMoney->getTotalMoney();
    }

    /**
     * @throws NotEnoughMoneyException
     * @throws ProductOutOfStockException
     */
    public function buy(Product $product): Purchase
    {
        $this->catalog->guardStockQuantity($product);
        $productPrice = $this->catalog->getPriceFor($product);
        $this->userMoney->guardHasEnoughMoney($productPrice);

        $this->machineMoney->add($this->userMoney);
        $change = $this->machineMoney->getChange($this->getUserMoney() - $productPrice);
        $this->catalog->decreaseStock($product);

        $this->context->updateState(ReadyVendingMachineState::createWithMoneyAndCatalog(
            $this->machineMoney,
            $this->catalog
        ));

        return new Purchase($product, $change);
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

    /**
     * @throws UnauthorizedActionException
     */
    public function startMaintenance(): void
    {
        throw new UnauthorizedActionException();
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
