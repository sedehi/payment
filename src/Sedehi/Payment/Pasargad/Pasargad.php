<?php


/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 11:23 PM
 */


namespace Sedehi\Payment\Pasargad;

use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;

class Pasargad extends PaymentAbstract implements PaymentInterface
{

    public function __construct($config)
    {
        $this->terminalId    = $config['terminalId'];
        $this->webserviceUrl = $config['webserviceUrl'];
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