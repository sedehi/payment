<?php

return array(
    'table' => 'payment_transaction',

    'default_provider' => 'mellat',

    'test' => true,

    'currency' => 'rial',

    'callback_url' => 'http://payment.dev:8080/return',

    'providers' => array(

        'payline' => array(
            'api'                => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'request_url'        => 'http://payline.ir/payment/gateway-send',
            'second_request_url' => 'http://payline.ir/payment/gateway-',
            'get_request_url'    => 'http://payline.ir/payment/gateway-result-second',
        ),

        'zarinpal' => array(
            'MerchantID' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',

            'payment_url' => 'https://www.zarinpal.com/pg/StartPay/',

            'server' => 'iran',

            'servers' => array(
                'germany' => array(
                    'request_url' => 'https://de.zarinpal.com/pg/services/WebGate/wsdl',
                ),
                'iran'    => array(
                    'request_url' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl'
                )
            ),

        ),

        'jahanpay' => array(
            'api'         => 'gt33345g394',
            'payment_url' => 'http://www.jahanpay.com/webservice?wsdl',
            'request_url' => 'http://www.jahanpay.com/pay_invoice/',
        ),

        'mellat' => array(
            'terminalId'   => '1844998',
            'username'      => 'x7962',
            'password'      => '41259193',
            'webserviceUrl' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
        )
    ),


    'test_providers' => array(
        'payline' => array(
            'api'                => 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567',
            'request_url'        => 'http://payline.ir/payment-test/gateway-send',
            'second_request_url' => 'http://payline.ir/payment-test/gateway-',
            'get_request_url'    => 'http://payline.ir/payment-test/gateway-result-second',
        )
    )
);
