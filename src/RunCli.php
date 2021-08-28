<?php

use App\Application\Command\CommandFactory;

require "vendor/autoload.php";

array_shift($argv);
$commandName = strtoupper(array_shift($argv));

try {
    $arguments = $argv ?? [];
    $command = CommandFactory::createByName($commandName);
    $result = $command->execute($arguments);
    echo $result . "\n";
} catch (Exception $exception) {
    echo $exception->getMessage() . "\n";
}

