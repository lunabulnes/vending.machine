<?php

namespace App\Application\UseCase;

use App\Domain\Catalog\Product;
use App\Domain\VendingMachine\Purchase\Purchase;
use App\Domain\VendingMachine\VendingMachineRepository;

class Buy
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(string $productName): Purchase
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $purchase = $vendingMachine->buy(Product::create($productName));
        $this->vendingMachineRepository->save($vendingMachine);

        return $purchase;
    }
}
