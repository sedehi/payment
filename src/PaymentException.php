<?php


namespace Sedehi\Payment;

use Exception;

class PaymentException extends Exception
{

    public function __construct($error, $errorCode)
    {
        parent::__construct($error, $errorCode);
    }
}