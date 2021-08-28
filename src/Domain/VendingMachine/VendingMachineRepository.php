<?php

namespace App\Domain\VendingMachine;

interface VendingMachineRepository
{
    public function get(): VendingMachine;
    public function save(VendingMachine $vendingMachine): void ;
}
