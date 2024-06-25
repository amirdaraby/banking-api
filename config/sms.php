<?php

return [

    'provider' => env('SMS_PROVIDER'),

    'providers' => [
        'kavenegar' => [
            'api_key' => env('KAVENEGAR_API_KEY'),
            'sender' => env('KAVENEGAR_SENDER'),
        ],

        'ghasedak' => [
            'api_key' => env('GHASEDAK_API_KEY'),
        ],
    ],

];
