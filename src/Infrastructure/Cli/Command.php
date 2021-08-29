<?php

namespace App\Infrastructure\Cli;

interface Command
{
    /**
     * @param array<string> $args
     * @return mixed
     */
    public function __invoke(array $args);
}
