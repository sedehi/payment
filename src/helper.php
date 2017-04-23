<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 4/23/2017 AD
 * Time: 1:30 PM
 */
if(!function_exists('payment_convert_amount')){
    function payment_convert_amount($amount, $provider)
    {
        $configCurrency   = config('payment.currency');
        $providerCurrency = config('payment.providers.'.$provider.'.currency');
        if($configCurrency == $providerCurrency){
            return $amount;
        }
        if($configCurrency == 'rial' && $providerCurrency == 'toman'){
            return $amount * 10;
        }elseif($configCurrency == 'toman' && $providerCurrency == 'rial'){
            return $amount / 10;
        }

        return null;
    }
}