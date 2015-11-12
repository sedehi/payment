<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\JahanPay;


class JahanPay {

    private $api;

    public function __construct($api) {

        $this->api = $api;
    }

    public function request($amount, $url, $redirect) {

    }

    public function verify($url, $trans_id, $id_get) {


    }
}