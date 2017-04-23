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
    public static function convert($amount, $provider)
    {
        $configCurrency = config('payment.currency');
        $providerCurrency = self::type($provider);
        if($configCurrency == $providerCurrency){
            return $amount;
        }
        if($configCurrency == 'rial' && $providerCurrency == 'toman'){
            return $amount / 10;
        }elseif($configCurrency == 'toman' && $providerCurrency == 'rial'){
            return $amount * 10;
        }

        return null;
    }

    public static function type($provider)
    {
        return config('payment.providers.'.$provider.'.currency');
    }
}