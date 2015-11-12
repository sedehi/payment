<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\ZarinPal;

use SoapClient;

class ZarinPal {

    private $merchantID;

    public function __construct($merchantID) {

        $this->merchantID = $merchantID;
    }

    public function request($amount, $url, $callbackURL, $description) {

        $client = new SoapClient($url, array('encoding' => 'UTF-8'));

        $result = $client->PaymentRequest(
            array(
                'MerchantID'  => $this->merchantID,
                'Amount'      => $amount,
                'CallbackURL' => $callbackURL,
                'Description' => $description,
                'Mobile'      => '',
                'Email'       => ''
            )
        );

        return $result;

    }

    public function verify($url, $Authority, $Amount) {


        $client = new SoapClient($url, array('encoding' => 'UTF-8'));

        $result = $client->PaymentVerification(
            array(
                'MerchantID' => $this->merchantID,
                'Authority'  => $Authority,
                'Amount'     => $Amount
            )
        );

        return $result;

    }
}