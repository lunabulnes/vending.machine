<?php

namespace App\Domain\VendingMachine\Exception;

use Exception;

class MachineOutOfServiceException extends Exception
{
    /** @var string $message */
    protected $message = 'MACHINE_OUT_OF_SERVICE';
}
