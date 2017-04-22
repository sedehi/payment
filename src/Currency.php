<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/26/2015
 * Time: 12:31 AM
 */

namespace Sedehi\Payment;

class Currency
{
    private static $providerCurrency = array(
        'zarinpal' => 'toman',
        'mellat'   => 'rial'
    );

    public static function convert($amount, $provider)
    {

        $configCurrency = config('payment.currency');

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