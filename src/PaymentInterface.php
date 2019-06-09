<?php

namespace Sedehi\Payment;

interface PaymentInterface
{

    public function request();

    public function verify();

    public function reversal();

}