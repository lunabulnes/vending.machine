<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Money\Change;
use App\Domain\Money\Coin;

class VendingMachine
{
    private $userChange;

    private function __construct(Change $userChange){
        $this->userChange = $userChange;
    }

    public static function create()
    {
        return new self(Change::create());
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->userChange->addCoin($coin);
    }

    public function getUserMoney(): float
    {
        return $this->userChange->getTotalMoney();
    }

    public function returnUserMoney(): array
    {
        $userCoins = $this->userChange->returnMoney();
        $this->userChange->resetMoney();
        return $userCoins;
    }
}
