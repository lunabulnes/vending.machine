<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\StopMaintenance;
use App\Infrastructure\Cli\Command;

class StopMaintenanceCommand implements Command
{
    private StopMaintenance $stopMaintenanceMode;

    public function __construct(StopMaintenance $stopMaintenanceMode)
    {
        $this->stopMaintenanceMode = $stopMaintenanceMode;
    }

    public function __invoke(array $args): void
    {
        $this->stopMaintenanceMode->execute();
    }
}
