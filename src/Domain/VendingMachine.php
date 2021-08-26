<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\NotEnoughMoneyException;
use App\Domain\Catalog\Exception\ProductOutOfStockException;
use App\Domain\Catalog\Product;
use App\Domain\Money\Money;
use App\Domain\Money\Coin;

class VendingMachine
{
    private $userMoney;
    private $catalog;

    private function __construct(Money $userMoney, Catalog $catalog)
    {
        $this->userMoney = $userMoney;
        $this->catalog = $catalog;
    }

    public static function create()
    {
        return new self(Money::create(), Catalog::create());
    }

    public static function createWithCatalog(Catalog $catalog)
    {
        return new self(Money::create(), $catalog);
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

    /**
     * @throws NotEnoughMoneyException
     * @throws ProductOutOfStockException
     */
    public function buy(Product $product): Product
    {
        $this->catalog->guardQuantity($product);

        if ($this->getAvailableMoney() < $this->catalog->getPriceFor($product)) {
            throw new NotEnoughMoneyException();
        }

        return $product;
    }
}
