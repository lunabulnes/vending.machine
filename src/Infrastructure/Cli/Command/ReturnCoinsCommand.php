<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\ReturnCoins;
use App\Infrastructure\Cli\Command;

class ReturnCoinsCommand implements Command
{
    private $returnCoins;

    public function __construct(ReturnCoins $returnCoins)
    {
        $this->returnCoins = $returnCoins;
    }

    public function __invoke(array $args)
    {
        return json_encode($this->returnCoins->execute());
    }
}
