<?php

namespace App\Infrastructure\Cli;

interface Command
{
    public function __invoke(array $args);
}
