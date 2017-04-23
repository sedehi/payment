<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 11:14 PM
 */

namespace Sedehi\Payment\Providers\Pasargad;

use Sedehi\Payment\PaymentException;

class PasargadException extends PaymentException
{

    public static $errors = [];

    public function __construct($errorId)
    {
        parent::__construct(@self::$errors[$errorId], $errorId);
    }

}