<?php namespace App\Aarks\GeneralLedger;

use Exception;

class InvalidValueException extends Exception
{
    protected $message = "Trying to set an invalid value (Check Valid Value List)";

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
