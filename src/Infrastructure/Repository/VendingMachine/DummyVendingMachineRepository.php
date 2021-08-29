<?php

namespace App\Infrastructure\Repository\VendingMachine;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\Money\Coin;
use App\Domain\Money\Exception\InvalidCoinException;
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
            $state = $this->getDefaultState();
        }
        return VendingMachineContext::createWithState($state);
    }

    public function save(VendingMachine $vendingMachine): void
    {
        file_put_contents(self::FILE, json_encode($vendingMachine));
    }

    /**
     * @param array<int, array> $jsonMoney
     * @throws InvalidCoinException
     */
    private function hydrateMoney(array $jsonMoney): Money
    {
        $coins = [];
        foreach ($jsonMoney as $moneyItem) {
            while ($moneyItem['quantity'] > 0) {
                $coins[] = Coin::create($moneyItem['coinType'] * 100);
                $moneyItem['quantity'] -= 1;
            }
        }
        return Money::createFromCoins($coins);
    }

    /**
     * @param array<int, array> $jsonCatalog
     */
    private function hydrateCatalog(array $jsonCatalog): Catalog
    {
        $catalog = Catalog::create();

        foreach ($jsonCatalog as $catalogItem) {
            $product = Product::create($catalogItem['productName']);
            $stock = Stock::create($product, $catalogItem['price'] * 100, $catalogItem['quantity']);
            $catalog->addStock($stock);
        }
        return $catalog;
    }

    private function hydrateState(): VendingMachineState
    {
        $string = file_get_contents(self::FILE);

        $jsonContent = json_decode(strval($string), true);
        $stateClass = $jsonContent['state']['class'];
        $jsonUserMoney = isset($jsonContent['state']['userMoney']) ? $jsonContent['state']['userMoney'] : [];
        $jsonMachineMoney = isset($jsonContent['state']['machineMoney']) ? $jsonContent['state']['machineMoney'] : [];
        $jsonCatalog = isset($jsonContent['state']['catalog']) ? $jsonContent['state']['catalog'] : [];

        $userMoney = $this->hydrateMoney($jsonUserMoney);
        $machineMoney = $this->hydrateMoney($jsonMachineMoney);
        $catalog = $this->hydrateCatalog($jsonCatalog);
        return $stateClass::createWithMoneyAndCatalog($machineMoney, $catalog, $userMoney);
    }

    private function getDefaultState(): ReadyVendingMachineState
    {
        $catalog = $this->hydrateCatalog(
            [
                ['productName' => 'Water', 'price' => 0.65, 'quantity' => 1],
                ['productName' => 'Juice', 'price' => 1, 'quantity' => 1],
                ['productName' => 'Soda', 'price' => 1.50, 'quantity' => 1],
            ]
        );
        $money = $this->hydrateMoney(
            [
                ['coinType' => 1, 'quantity' => 10],
                ['coinType' => 0.25, 'quantity' => 10],
                ['coinType' => 0.10, 'quantity' => 10],
                ['coinType' => 0.05, 'quantity' => 10],
            ]
        );
        return ReadyVendingMachineState::createWithMoneyAndCatalog($money, $catalog);
    }
}
