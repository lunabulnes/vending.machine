<?php

namespace App\Infrastructure\Cli;

use App\Application\UseCase\AddChange;
use App\Application\UseCase\AddCoin;
use App\Application\UseCase\Buy;
use App\Application\UseCase\GetCatalog;
use App\Application\UseCase\GetUserTotal;
use App\Application\UseCase\ReturnCoins;
use App\Infrastructure\Cli\Command\AddChangeCommand;
use App\Infrastructure\Cli\Command\AddCoinCommand;
use App\Infrastructure\Cli\Command\BuyCommand;
use App\Infrastructure\Cli\Command\GetUserTotalCommand;
use App\Infrastructure\Cli\Exception\InvalidCommandException;
use App\Infrastructure\Cli\Command\GetCatalogCommand;
use App\Infrastructure\Cli\Command\ReturnCoinsCommand;
use App\Application\UseCase\AddStock;
use App\Application\UseCase\StartMaintenance;
use App\Application\UseCase\StopMaintenance;
use App\Infrastructure\Cli\Command\AddStockCommand;
use App\Infrastructure\Cli\Command\StartMaintenanceCommand;
use App\Infrastructure\Cli\Command\StopMaintenanceCommand;
use App\Infrastructure\Repository\VendingMachine\DummyVendingMachineRepository;

class CommandFactory
{
    /**
     * @throws InvalidCommandException
     */
    public static function createByName(string $commandName): Command
    {
        switch ($commandName) {
            case "ADDCOIN":
                return new AddCoinCommand(new AddCoin(new DummyVendingMachineRepository()));
            case "RETURNCOINS":
                return new ReturnCoinsCommand(new ReturnCoins(new DummyVendingMachineRepository()));
            case "GETUSERTOTAL":
                return new GetUserTotalCommand(new GetUserTotal(new DummyVendingMachineRepository()));
            case "BUY":
                return new BuyCommand(new Buy(new DummyVendingMachineRepository()));
            case "GETCATALOG":
                return new GetCatalogCommand(new GetCatalog(new DummyVendingMachineRepository()));
            case "STARTMAINTENANCE":
                return new StartMaintenanceCommand(new StartMaintenance(new DummyVendingMachineRepository()));
            case "STOPMAINTENANCE":
                return new StopMaintenanceCommand(new StopMaintenance(new DummyVendingMachineRepository()));
            case "ADDSTOCK":
                return new AddStockCommand(new AddStock(new DummyVendingMachineRepository()));
            case "ADDCHARGE":
                return new AddChangeCommand(new AddChange(new DummyVendingMachineRepository()));
        }

        throw new InvalidCommandException();
    }
}
