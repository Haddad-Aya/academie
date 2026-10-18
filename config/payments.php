<?php

return [
    // manual : paiement sur place / virement ; konnect : caisse hébergée Konnect.
    'driver' => env('PAYMENT_DRIVER', 'manual'),

    'konnect' => [
        'api_url' => env('KONNECT_API_URL', 'https://api.sandbox.konnect.network/api/v2'),
        'api_key' => env('KONNECT_API_KEY', ''),
        'wallet_id' => env('KONNECT_WALLET_ID', ''),
        'currency' => env('KONNECT_CURRENCY', 'TND'),
        'accepted_payment_methods' => array_filter(array_map('trim', explode(',', env('KONNECT_PAYMENT_METHODS', 'wallet,bank_card,e-DINAR')))),
        'lifespan' => (int) env('KONNECT_PAYMENT_LIFESPAN', 30),
    ],

    'currency' => 'TND',
];
