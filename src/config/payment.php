<?php
return [
    'table'            => 'payment_transaction',
    'default_provider' => 'mellat',
    'test'             => true,
    'currency'         => 'rial',
    'callback_url'     => 'http://develop.dev/return',
    'providers'        => [
        'parsian'  => [
            'currency'       => 'rial',
            'terminal_id'    => 'xxxxxx',
            'webservice_url' => 'https://pec.shaparak.ir/pecpaymentgateway/eshopservice.asmx',
            'gate_url'       => 'https://pec.shaparak.ir/pecpaymentgateway/default.aspx?au=',
        ],
        'pasargad' => [
            'currency'    => 'rial',
            'terminal_id' => 'xxxxx',
            'merchant_id' => 'xxxxx',
            'gate_url'    => 'https://pep.shaparak.ir/gateway.aspx',
            'verify_url'  => 'https://pep.shaparak.ir/VerifyPayment.aspx',
            'check_url'   => 'https://pep.shaparak.ir/CheckTransactionResult.aspx',
        ],
        'zarinpal' => [
            'currency'    => 'toman',
            'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'payment_url' => 'https://www.zarinpal.com/pg/StartPay/',
            'zarin_gate' => false,
            'server'      => 'iran',
            'servers'     => [
                'germany' => [
                    'request_url' => 'https://de.zarinpal.com/pg/services/WebGate/wsdl',
                ],
                'iran'    => [
                    'request_url' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
                ],
            ],
        ],
        'mellat'   => [
            'currency'       => 'rial',
            'terminal_id'    => 'xxxxxxx',
            'username'       => 'xxxxxxx',
            'password'       => 'xxxxxxx',
            'webservice_url' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
        ],
    ],
    'test_providers'   => [
        'zarinpal' => [
            'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'payment_url' => 'https://sandbox.zarinpal.com/pg/StartPay/',
            'request_url' => 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl',
        ],
    ],
];
