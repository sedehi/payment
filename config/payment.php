<?php
return [
    'default_provider' => 'mellat',
    'test'             => true,
    'currency'         => 'rial',
    'callback_url'     => 'http://develop.dev/return',
    'providers'        => [
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
