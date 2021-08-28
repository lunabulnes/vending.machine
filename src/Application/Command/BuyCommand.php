<?php

namespace App\Application\Command;

use App\Domain\Catalog\Product;
use App\Domain\VendingMachine\VendingMachineRepository;

class BuyCommand implements Command
{
    private $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(array $args)
    {
        $vendingMachine = $this->vendingMachineRepository->get();
        $purchase = $vendingMachine->buy(Product::create($args[0]));
        $this->vendingMachineRepository->save($vendingMachine);

        return json_encode($purchase);
    }
}
