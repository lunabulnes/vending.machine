<?php

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\Purchase\Purchase;
use JsonSerializable;

interface VendingMachine extends JsonSerializable
{
    public function getCatalog(): Catalog;
    public function addUserCoin(Coin $coin): void;
    public function getUserMoney(): int;
    public function returnUserCoins(): array;
    public function getMachineMoney(): int;
    public function buy(Product $product): Purchase;
    public function addStock(Stock $catalog): void;
    public function refillChange(Money $change): void;
    public function startMaintenance(): void;
    public function stopMaintenance(): void;
}
