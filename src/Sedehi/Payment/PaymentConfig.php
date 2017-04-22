<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/2/2015
 * Time: 1:02 AM
 */

namespace Sedehi\Payment;

class PaymentConfig
{

    public static function get($provider)
    {
        $config = [];
        switch($provider){
            case 'mellat':
                $config['terminalId']    = config('payment.providers.mellat.terminalId');
                $config['username']      = config('payment.providers.mellat.username');
                $config['password']      = config('payment.providers.mellat.password');
                $config['webserviceUrl'] = config('payment.providers.mellat.webserviceUrl');
            break;
            case 'zarinpal':
                if(config('payment.test')){
                    $config['payment_url'] = config('payment.test_providers.zarinpal.payment_url');
                    $config['merchantId']  = config('payment.test_providers.zarinpal.merchantId');
                    $config['request_url'] = config('payment.test_providers.zarinpal.request_url');
                }else{
                    $config['server']      = config('payment.providers.zarinpal.server');
                    $config['payment_url'] = config('payment.providers.zarinpal.payment_url');
                    $config['merchantId']  = config('payment.providers.zarinpal.merchantId');
                    $config['request_url'] = config('payment.providers.zarinpal.servers.'.$config["server"].'.request_url');
                }
            break;
            case 'parsian':
                $config['terminalId']    = config('payment.providers.parsian.terminalId');
                $config['webserviceUrl'] = config('payment.providers.parsian.webserviceUrl');
                $config['gateUrl']       = config('payment.providers.parsian.gateUrl');
            break;
            case 'pasargad':
                $config['terminalId'] = config('payment.providers.pasargad.terminalId');
                $config['merchantId'] = config('payment.providers.pasargad.merchantId');
                $config['gateUrl']    = config('payment.providers.pasargad.gateUrl');
                $config['verifyUrl']  = config('payment.providers.pasargad.verifyUrl');
                $config['checkUrl']   = config('payment.providers.pasargad.checkUrl');
            break;
            default:
                throw new PaymentException('تنظیمات سرویس دهندی موردنظر یافت نشد', 1505);
        }

        return $config;
    }
}