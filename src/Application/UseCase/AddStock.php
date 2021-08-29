<?php

namespace App\Application\UseCase;

use App\Domain\Catalog\Exception\InvalidPriceException;
use App\Domain\Catalog\Exception\InvalidProductException;
use App\Domain\Catalog\Exception\InvalidQuantityException;
use App\Domain\Catalog\Product;
use App\Domain\Catalog\Stock;
use App\Domain\VendingMachine\VendingMachineRepository;

class AddStock
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @throws InvalidPriceException
     * @throws InvalidProductException
     * @throws InvalidQuantityException
     */
    public function execute(string $productName, int $price, int $quantity): void
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $stock = Stock::create(Product::create($productName), $price, $quantity);
        $vendingMachine->addStock($stock);
        $this->vendingMachineRepository->save($vendingMachine);
    }
}
