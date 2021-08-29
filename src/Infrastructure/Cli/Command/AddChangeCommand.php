<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\AddChange;
use App\Domain\Money\Exception\InvalidCoinException;
use App\Infrastructure\Cli\Command;
use App\Infrastructure\Cli\Exception\MissingArgumentException;

class AddChangeCommand implements Command
{
    private AddChange $addChange;

    public function __construct(AddChange $addChange)
    {
        $this->addChange = $addChange;
    }

    /**
     * @throws InvalidCoinException
     * @throws MissingArgumentException
     */
    public function __invoke(array $args): void
    {
        if (!isset($args[0]) || !intval($args[0])) {
            throw new MissingArgumentException('coin_value');
        }
        if (!isset($args[1]) || !intval($args[1])) {
            throw new MissingArgumentException('quantity');
        }

        $this->addChange->execute(intval($args[0]), intval($args[1]));
    }
}
