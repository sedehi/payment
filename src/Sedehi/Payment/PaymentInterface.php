<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 10:49 PM
 */

namespace Sedehi\Payment;


interface PaymentInterface
{

    public function request();

    public function verify();

    public function reversal();



}