<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 9:06 PM
 */

namespace Sedehi\Payment;

use Exception;

class PaymentException extends Exception
{

    public function __construct($error, $errorCode)
    {
        parent::__construct($error, $errorCode);
    }
}