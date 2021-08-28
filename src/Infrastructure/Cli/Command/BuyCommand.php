<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\Buy;
use App\Infrastructure\Cli\Command;
use App\Infrastructure\Cli\Exception\MissingArgumentException;

class BuyCommand implements Command
{
    private $buy;

    public function __construct(Buy $buy)
    {
        $this->buy = $buy;
    }

    /**
     * @throws MissingArgumentException
     */
    public function __invoke(array $args)
    {
        if (!isset($args[0]) || !is_string($args[0])) {
            throw new MissingArgumentException('product_name');
        }
        return json_encode($this->buy->execute($args[0]));
    }
}
