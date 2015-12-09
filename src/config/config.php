<?php

return [
    'table' => 'payment_transaction',

    'default_provider' => 'mellat',

    'test' => true,

    'currency' => 'rial',

    'callback_url' => 'http://develop.dev:8080/return',

    'providers' => [

        'payline' => [
            'api'                => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'request_url'        => 'http://payline.ir/payment/gateway-send',
            'second_request_url' => 'http://payline.ir/payment/gateway-',
            'get_request_url'    => 'http://payline.ir/payment/gateway-result-second',
        ],

        'parsian' => [
            'terminalId'    => 'xxxxxx',
            'webserviceUrl' => 'https://pec.shaparak.ir/pecpaymentgateway/eshopservice.asmx',
            'gateUrl'       => 'https://pec.shaparak.ir/pecpaymentgateway/default.aspx?au='
        ],

        'pasargad' => [
            'terminalId' => 'xxxxx',
            'merchantId' => 'xxxxx',
            'gateUrl'    => 'https://pep.shaparak.ir/gateway.aspx',
            'verifyUrl'  => 'https://pep.shaparak.ir/VerifyPayment.aspx',
            'checkUrl'   => 'https://pep.shaparak.ir/CheckTransactionResult.aspx'

        ],

        'zarinpal' => [
            'MerchantID'  => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'payment_url' => 'https://www.zarinpal.com/pg/StartPay/',
            'server'      => 'iran',
            'servers'     => [
                'germany' => [
                    'request_url' => 'https://de.zarinpal.com/pg/services/WebGate/wsdl',
                ],
                'iran'    => [
                    'request_url' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl'
                ]
            ],

        ],

        'jahanpay' => [
            'direct'              => true,
            'api'                 => 'xxxxxxxx',
            'webserviceUrl'       => 'http://www.jahanpay.com/webservice?wsdl',
            'requestUrl'          => 'http://www.jahanpay.com/pay_invoice/',
            'directWebserviceUrl' => 'http://www.jahanpay.com/directservice?wsdl',
        ],

        'mellat' => [
            'terminalId'    => 'xxxxxxx',
            'username'      => 'xxxxxxx',
            'password'      => 'xxxxxxx',
            'webserviceUrl' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
        ],

    ],


    'test_providers' => [
        'payline' => [
            'api'                => 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567',
            'request_url'        => 'http://payline.ir/payment-test/gateway-send',
            'second_request_url' => 'http://payline.ir/payment-test/gateway-',
            'verify_request_url' => 'http://payline.ir/payment-test/gateway-result-second',
        ]
    ]
];
