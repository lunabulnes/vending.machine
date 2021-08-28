<?php

namespace App\Application\Command;

interface CommandInterface
{
    public function execute(array $args): string ;
}
