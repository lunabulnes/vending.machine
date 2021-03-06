<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\StartMaintenance;
use App\Infrastructure\Cli\Command;

class StartMaintenanceCommand implements Command
{
    private StartMaintenance $startMaintenance;

    public function __construct(StartMaintenance $startMaintenance)
    {
        $this->startMaintenance = $startMaintenance;
    }

    public function __invoke(array $args): void
    {
        $this->startMaintenance->execute();
    }
}
