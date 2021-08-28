<?php

namespace App\Application\Command;

use App\Application\Command\Exception\InvalidCommandException;

class CommandFactory
{
    public static function createByName(string $commandName): CommandInterface
    {
        switch ($commandName) {
            case "ADDCOIN":
                return new AddCoinCommand();
        }

        throw new InvalidCommandException();
    }
}
