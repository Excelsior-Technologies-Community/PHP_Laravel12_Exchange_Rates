<?php

return [

    'driver' => 'exchange-rate-host',

    'cache' => [

        'enabled' => true,
        'ttl' => 3600,

    ],

    'drivers' => [

        'exchange-rate-host' => [

            'api_key' => env('EXCHANGE_RATES_API_KEY'),

        ],

    ],

];