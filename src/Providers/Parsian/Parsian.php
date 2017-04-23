<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 10:35 PM
 */

namespace Sedehi\Payment\Providers\Parsian;

use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;

class Parsian extends PaymentAbstract implements PaymentInterface
{

    private $terminalId;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $authority;
    public $customData  = [];

    public function __construct($config)
    {
        $this->terminalId    = $config['terminalId'];
        $this->webserviceUrl = $config['webserviceUrl'];
        $this->gateUrl       = $config['gateUrl'];
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