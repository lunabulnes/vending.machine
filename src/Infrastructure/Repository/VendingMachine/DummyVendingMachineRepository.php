<?php

namespace App\Infrastructure\Repository\VendingMachine;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Money;
use App\Domain\VendingMachine\State\ReadyVendingMachineState;
use App\Domain\VendingMachine\State\VendingMachineState;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\VendingMachine\Context\VendingMachineContext;
use App\Domain\VendingMachine\VendingMachineRepository;

class DummyVendingMachineRepository implements VendingMachineRepository
{
    private const FILE = 'vending_machine.JSON';

    public function get(): VendingMachine
    {
        if (file_exists(self::FILE)) {
            $state = $this->hydrateState();
        } else {
            $state = ReadyVendingMachineState::create();
        }
        return VendingMachineContext::createWithState($state);
    }

    public function save(VendingMachine $vendingMachine): void
    {
        file_put_contents(self::FILE, json_encode($vendingMachine));
    }

    private function hydrateMoney(array $jsonMoney): Money
    {
        $coins = [];
        foreach ($jsonMoney as $moneyItem) {
            while ($moneyItem['quantity'] > 0) {
                $coins[] = Coin::create($moneyItem['coinType']);
                $moneyItem['quantity'] -= 1;
            }
        }
        return Money::createFromCoins($coins);
    }

    private function hydrateCatalog(array $jsonCatalog): Catalog
    {
        $catalog = Catalog::create();

        foreach ($jsonCatalog as $catalogItem) {
            $product = Product::create($catalogItem['productName']);
            $stock = new Stock($product, $catalogItem['price'], $catalogItem['quantity']);
            $catalog->refillStock($stock);
        }
        return $catalog;
    }

    private function hydrateState(): VendingMachineState
    {
        $string = file_get_contents(self::FILE);

        $jsonContent = json_decode($string, true);
        $stateClass = $jsonContent['state']['class'];
        $jsonUserMoney = isset($jsonContent['state']['userMoney']) ? $jsonContent['state']['userMoney'] : [];
        $jsonMachineMoney = isset($jsonContent['state']['machineMoney']) ? $jsonContent['state']['machineMoney'] : [];
        $jsonCatalog = isset($jsonContent['state']['catalog']) ? $jsonContent['state']['catalog'] : [];

        $userMoney = $this->hydrateMoney($jsonUserMoney);
        $machineMoney = $this->hydrateMoney($jsonMachineMoney);
        $catalog = $this->hydrateCatalog($jsonCatalog);
        return $stateClass::createWithMoneyAndCatalog($machineMoney, $catalog, $userMoney);
    }
}
