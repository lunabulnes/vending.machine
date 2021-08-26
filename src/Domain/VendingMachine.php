<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Money\Coin;

class VendingMachine
{
    private $coins;

    private function __construct(){}

    public static function create()
    {
        return new self();
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getUserMoney(): float
    {
        $total = 0;
        foreach ($this->coins as $coin) {
            $total += $coin->value();
        }
        return $total;
    }
}
