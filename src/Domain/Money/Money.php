<?php
declare(strict_types = 1);

namespace App\Domain\Money;

class Money
{
    private $coins;

    private function __construct(array $coins)
    {
        $this->coins = $coins;
    }

    public static function create(array $coins = [])
    {
        return new self($coins);
    }

    public function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getTotalMoney(): float
    {
        $total = 0;
        foreach ($this->coins as $coin) {
            $total += $coin->value();
        }
        return $total;
    }

    public function returnCoins(): array
    {
        $coins = $this->coins;
        $this->resetMoney();
        return $coins;
    }

    public function resetMoney(): void
    {
        $this->coins = [];
    }
}