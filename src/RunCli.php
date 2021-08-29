<?php

use App\Infrastructure\Cli\CommandFactory;

require "vendor/autoload.php";

array_shift($argv);
$commandName = strtoupper(array_shift($argv));

try {
    $arguments = $argv ?? [];
    $command = CommandFactory::createByName($commandName);
    $response = $command($arguments);
    echo $response . "\n";
} catch (Exception $exception) {
    echo $exception->getMessage() . "\n";
}
