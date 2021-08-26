<?php

namespace App\Domain;

class VendingMachine
{
    private function __construct(){}

    public static function create()
    {
        return new self();
    }
}
