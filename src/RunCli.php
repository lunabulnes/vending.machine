<?php

use App\Application\Command\CommandFactory;

require "vendor/autoload.php";

array_shift($argv);
$commandName = strtoupper(array_shift($argv));

try {
    $arguments = $argv ?? [];
    $command = CommandFactory::createByName($commandName);
    $response = $command->execute($arguments);
    echo $response . "\n";
} catch (Exception $exception) {
    echo $exception->getMessage() . "\n";
}

