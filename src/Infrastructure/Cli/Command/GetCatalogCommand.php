<?php

namespace App\Infrastructure\Cli\Command;

use App\Application\UseCase\GetCatalog;
use App\Infrastructure\Cli\Command;

class GetCatalogCommand implements Command
{
    private GetCatalog $getCatalog;

    public function __construct(GetCatalog $getCatalog)
    {
        $this->getCatalog = $getCatalog;
    }

    public function __invoke(array $args): string
    {
        return json_encode($this->getCatalog->execute());
    }
}
