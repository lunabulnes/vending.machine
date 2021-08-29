<?php

namespace App\Domain\Money;

use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use JsonSerializable;

class Money implements JsonSerializable
{
    /**
     * @var array<int, array> $groupedCoins
     */
    private array $groupedCoins;

    /**
     * @param array<Coin> $coins
     */
    private function __construct(array $coins)
    {
        $this->groupedCoins = [];
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

    public function addCoin(Coin $coin): void
    {
        $this->groupedCoins[$coin->value()][] = $coin;
    }

    public function getTotalMoney(): int
    {
        $total = 0;
        foreach ($this->groupedCoins as $coinType => $coins) {
            $total += $coinType * count($coins);
        }

        return $total;
    }

    /**
     * @return array<Coin>
     */
    public function returnCoins(): array
    {
        $TotalCoins = [];
        foreach ($this->groupedCoins as $coinType => $coins) {
            $TotalCoins = array_merge($TotalCoins, $coins);
        }
        $this->resetMoney();
        return $TotalCoins;
    }

    public function resetMoney(): void
    {
        $this->groupedCoins = [];
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
        foreach ($money->groupedCoins as $coinType => $coins) {
            $this->groupedCoins[$coinType] = array_merge($this->groupedCoins[$coinType] ?? [], $coins);
        }
    }

    public function subtract(Money $money): void
    {
        foreach ($money->groupedCoins as $coinType => $coins) {
            $this->groupedCoins[$coinType] = array_slice($this->groupedCoins[$coinType], count($coins));
            if (count($this->groupedCoins[$coinType]) === 0) {
                unset($this->groupedCoins[$coinType]);
            }
        }
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
        foreach ($this->groupedCoins as $coinType => $coins) {
            if ($coinType <= $amount) {
                while (count($coins) > 0 && $amount > 0) {
                    $changeCoins[] = array_shift($coins);
                    $amount -= $coinType;
                }
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
        foreach ($this->groupedCoins as $coinType => $coins) {
            $this->groupedCoins[$coinType] = array_merge($this->groupedCoins[$coinType] ?? [], $coins);
        }
        return $coins;
    }

    /**
     * @return array<int, mixed>
     */
    public function jsonSerialize(): array
    {
        $temp = [];
        foreach ($this->groupedCoins as $coinType => $coins) {
            $temp[] = [
                'coinType' => $coinType,
                'quantity' => count($coins),
            ];
        }
        return $temp;
    }
}
