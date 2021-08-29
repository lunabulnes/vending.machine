<?php

namespace App\Domain\Money;

use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use JsonSerializable;

class Money implements JsonSerializable
{
    /** @var array<int, int> $coinTypes */
    private array $coinTypes;

    /**
     * @param array<Coin> $coins
     */
    private function __construct(array $coins)
    {
        $this->coinTypes = [];
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    /**
     * @param array<Coin> $coins
     */
    public static function createFromCoins(array $coins = []): Money
    {
        return new self($coins);
    }

    public function addCoin(Coin $coin, int $quantity = 1): void
    {
        if (isset($this->coinTypes[$coin->value()])) {
            $this->coinTypes[$coin->value()] += $quantity;
        } else {
            $this->coinTypes[$coin->value()] = $quantity;
        }
        $this->sortCoinTypesDesc();
    }

    public function getTotalMoney(): int
    {
        $total = 0;
        foreach ($this->coinTypes as $coinType => $quantity) {
            $total += $coinType * $quantity;
        }

        return $total;
    }

    /**
     * @return array<Coin>
     */
    public function returnCoins(): array
    {
        $TotalCoins = $this->getCoins();
        $this->resetMoney();
        return $TotalCoins;
    }

    public function resetMoney(): void
    {
        $this->coinTypes = [];
    }

    /**
     * @throws NotEnoughMoneyException
     */
    public function guardHasEnoughMoney(int $amount): void
    {
        if ($this->getTotalMoney() < $amount) {
            throw new NotEnoughMoneyException();
        }
    }

    public function add(Money $money): void
    {
        foreach ($money->coinTypes as $coinType => $quantity) {
            $this->addCoin(Coin::create($coinType), $quantity);
        }

        $this->sortCoinTypesDesc();
    }

    public function subtract(Money $money): void
    {
        foreach ($money->coinTypes as $coinType => $quantity) {
            $this->coinTypes[$coinType] -= $quantity;
        }

        $this->sortCoinTypesDesc();
    }

    /**
     * @throws NotEnoughMoneyException
     */
    public function getChange(int $amount): ?Money
    {
        if ($amount <= 0) {
            return null;
        }

        $changeCoins = [];
        foreach ($this->coinTypes as $coinType => $quantity) {
            while ($coinType <= $amount && $quantity > 0 && $amount > 0) {
                $changeCoins[] = Coin::create($coinType);
                $amount -= $coinType;
                $quantity--;
            }
        }

        if ($amount > 0) {
            throw new NotEnoughMoneyException();
        }

        $change = Money::createFromCoins($changeCoins);
        $this->subtract($change);

        return $change;
    }

    /**
     * @return array<Coin>
     */
    public function getCoins(): array
    {
        $coins = [];
        foreach ($this->coinTypes as $coinType => $quantity) {
            while ($quantity > 0) {
                $coins[] = Coin::create($coinType);
                $quantity--;
            }
        }
        return $coins;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        $temp = [];
        foreach ($this->coinTypes as $coinType => $quantity) {
            $temp[] = [
                'coinType' => $coinType / 100,
                'quantity' => $quantity,
            ];
        }
        return $temp;
    }

    private function sortCoinTypesDesc(): void
    {
        krsort($this->coinTypes);
    }
}
