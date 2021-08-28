<?php

namespace App\Application\Command;

use App\Application\Command\Exception\InvalidCommandException;
use App\Infrastructure\VendingMachine\DummyVendingMachineRepository;

class CommandFactory
{
    /**
     * @throws InvalidCommandException
     */
    public static function createByName(string $commandName): Command
    {
        switch ($commandName) {
            case "ADDCOIN":
                return new AddCoinCommand(new DummyVendingMachineRepository());
            case "RETURNCOINS":
                return new ReturnCoinsCommand(new DummyVendingMachineRepository());
            case "CHECKUSERTOTAL":
                return new CheckUserTotalCommand(new DummyVendingMachineRepository());
            case "BUY":
                return new BuyCommand(new DummyVendingMachineRepository());
        }

        throw new InvalidCommandException();
    }
}
