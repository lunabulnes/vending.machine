<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Catalog\Product;
use App\Domain\Money\Money;
use App\Domain\Money\Coin;

class VendingMachine
{
    private $userMoney;

    private function __construct(Money $userMoney){
        $this->userMoney = $userMoney;
    }

    public static function create()
    {
        return new self(Money::create());
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->userMoney->addCoin($coin);
    }

    public function getAvailableMoney(): float
    {
        return $this->userMoney->getTotalMoney();
    }

    public function returnUserCoins(): array
    {
        return $this->userMoney->returnCoins();
    }

    public function buy($product): Product
    {
        return $product;
    }
}
