<?php

return [
    'table' => 'payment_transaction',

    'default_provider' => 'mellat',

    'test' => true,

    'currency' => 'rial',

    'callback_url' => 'http://develop.dev:8080/return',

    'providers' => [
        
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
            'merchantId'  => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
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

        'mellat' => [
            'terminalId'    => 'xxxxxxx',
            'username'      => 'xxxxxxx',
            'password'      => 'xxxxxxx',
            'webserviceUrl' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
        ],

    ],


    'test_providers' => [

    ]
];
