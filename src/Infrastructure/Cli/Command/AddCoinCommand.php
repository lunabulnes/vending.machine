<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\AddCoin;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Infrastructure\Cli\Command;
use App\Infrastructure\Cli\Exception\MissingArgumentException;

class AddCoinCommand implements Command
{
    private AddCoin $addCoin;

    public function __construct(AddCoin $addCoin)
    {
        $this->addCoin = $addCoin;
    }

    /**
     * @throws InvalidCoinException
     * @throws MissingArgumentException
     */
    public function __invoke(array $args): void
    {
        if (!isset($args[0]) || !floatval($args[0])) {
            throw new MissingArgumentException('value');
        }
        $this->addCoin->execute(intval(floatval($args[0]) * 100));
    }
}
