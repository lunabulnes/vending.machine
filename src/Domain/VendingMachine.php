<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Money\Cash;
use App\Domain\Money\Coin;

class VendingMachine
{
    private $userCash;

    private function __construct(Cash $userCash){
        $this->userCash = $userCash;
    }

    public static function create()
    {
        return new self(Cash::create());
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->userCash->addCoin($coin);
    }

    public function getAvailableCash(): float
    {
        return $this->userCash->getTotalMoney();
    }

    public function returnUserCash(): array
    {
        return $this->userCash->returnCoins();
    }
}
