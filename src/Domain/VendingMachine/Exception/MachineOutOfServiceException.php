<?php

namespace App\Domain\VendingMachine\Exception;

use Exception;

class MachineOutOfServiceException extends Exception
{
    protected $message = 'MACHINE_OUT_OF_SERVICE';
}
