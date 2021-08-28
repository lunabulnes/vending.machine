<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\AddCoin;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Infrastructure\Cli\Command;
use App\Infrastructure\Cli\Exception\MissingArgumentException;

class AddCoinCommand implements Command
{
    private $addCoin;

    public function __construct(AddCoin $addCoin)
    {
        $this->addCoin = $addCoin;
    }

    /**
     * @throws MissingArgumentException
     * @throws InvalidCoinException
     */
    public function __invoke(array $args)
    {
        if (!isset($args[0]) || !intval($args[0])) {
            throw new MissingArgumentException('value');
        }
        $this->addCoin->execute($args[0]);
    }
}
