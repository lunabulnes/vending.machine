<?php
declare(strict_types = 1);

namespace App\Domain\Money;

use App\Domain\Catalog\Exception\NotEnoughMoneyException;

class Money
{
    private $groupedCoins;

    private function __construct(array $coins)
    {
        $this->groupedCoins = [];
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    public static function createFromCoins(array $coins = [])
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

        return Money::createFromCoins($changeCoins);
    }
}
