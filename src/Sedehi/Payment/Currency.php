<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/26/2015
 * Time: 12:31 AM
 */

namespace Sedehi\Payment;

use Config;

class Currency
{

    private static $providerCurrency = array(
        'payline'  => 'rial',
        'zarinpal' => 'toman',
        'jahanpay' => 'toman',
        'mellat'   => 'rial'
    );

    public static function convert($amount, $provider)
    {

        $configCurrency = Config::get('payment::currency');

        if ($configCurrency == self::$providerCurrency[$provider]) {
            return $amount;
        }

        if ($configCurrency == 'rial' && self::$providerCurrency[$provider] == 'toman') {
            return $amount / 10;
        } elseif ($configCurrency == 'toman' && self::$providerCurrency[$provider] == 'rial') {
            return $amount * 10;
        }

        return $amount;
    }

    public static function type($provider)
    {
        return self::$providerCurrency[$provider];
    }
}