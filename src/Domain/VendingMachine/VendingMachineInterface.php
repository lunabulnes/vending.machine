<?php

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Product;
use App\Domain\Money\Coin;

interface VendingMachineInterface
{
    public function addUserCoin(Coin $coin): void;
    public function getUserMoney(): int;
    public function returnUserCoins(): array;
    public function getMachineMoney(): int;
    public function buy(Product $product): Purchase;
}
