<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 10:35 PM
 */

namespace Sedehi\Payment\Mellat;


use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;

class Parsian extends PaymentAbstract implements PaymentInterface
{

    public function __construct()
    {
    }

    public function request()
    {
        // TODO: Implement request() method.
    }

    public function verify($transaction)
    {
        // TODO: Implement verify() method.
    }

    public function reversal()
    {
        // TODO: Implement reversal() method.
    }
}