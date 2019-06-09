<?php

namespace Sedehi\Payment;

class PaymentConfig
{

    public static function get($provider){

        $config = [];
        switch($provider) {
            case 'mellat':
                $config['terminalId']    = config('payment.providers.mellat.terminal_id');
                $config['username']      = config('payment.providers.mellat.username');
                $config['password']      = config('payment.providers.mellat.password');
                $config['webserviceUrl'] = config('payment.providers.mellat.webservice_url');
                break;
            case 'zarinpal':
                if(config('payment.test')) {
                    $config['payment_url'] = config('payment.test_providers.zarinpal.payment_url');
                    $config['merchantId']  = config('payment.test_providers.zarinpal.merchant_id');
                    $config['request_url'] = config('payment.test_providers.zarinpal.request_url');
                }else {
                    $config['server']      = config('payment.providers.zarinpal.server');
                    $config['zarin_gate']  = config('payment.providers.zarinpal.zarin_gate');
                    $config['payment_url'] = config('payment.providers.zarinpal.payment_url');
                    $config['merchantId']  = config('payment.providers.zarinpal.merchant_id');
                    $config['request_url'] = config('payment.providers.zarinpal.servers.'.$config["server"].'.request_url');
                }
                break;
            case 'parsian':
                $config['terminalId']    = config('payment.providers.parsian.terminal_id');
                $config['webserviceUrl'] = config('payment.providers.parsian.webservice_url');
                $config['gateUrl']       = config('payment.providers.parsian.gate_url');
                break;
            case 'pasargad':
                $config['terminalId'] = config('payment.providers.pasargad.terminal_id');
                $config['merchantId'] = config('payment.providers.pasargad.merchant_id');
                $config['gateUrl']    = config('payment.providers.pasargad.gate_url');
                $config['verifyUrl']  = config('payment.providers.pasargad.verify_url');
                $config['checkUrl']   = config('payment.providers.pasargad.check_url');
                break;
            default:
                throw new PaymentException('تنظیمات سرویس دهندی موردنظر یافت نشد', 1505);
        }

        return $config;
    }
}
