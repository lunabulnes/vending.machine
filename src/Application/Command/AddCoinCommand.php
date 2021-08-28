<?php

namespace App\Application\Command;

class AddCoinCommand implements CommandInterface
{
    public function execute(array $args): string
    {
        return 'EXECUTED';
    }
}
