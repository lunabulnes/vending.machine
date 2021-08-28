<?php

namespace App\Application\Command;

interface Command
{
    public function execute(array $args);
}
