<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\GetUserTotal;
use App\Infrastructure\Cli\Command;

class GetUserTotalCommand implements Command
{
    private GetUserTotal $getUserTotal;

    public function __construct(GetUserTotal $getUserTotal)
    {
        $this->getUserTotal = $getUserTotal;
    }

    public function __invoke(array $args): int
    {
        return $this->getUserTotal->execute();
    }
}
