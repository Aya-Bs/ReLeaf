<?php

return [
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
        'guzzle' => [
            'verify' => false // Désactive la vérification SSL en développement
        ],
    ],
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'climatiq' => [
        'api_key' => env('CLIMATIQ_API_KEY'),
        'base_url' => 'https://beta3.api.climatiq.io/',
        'version' => 'v1',
    ],

    'carbon_interface' => [
        'api_key' => env('CARBON_INTERFACE_API_KEY'),
        'base_url' => 'https://www.carboninterface.com/api/v1/',
    ],
    'http_client' => [
        'global_verify' => false, // Désactive SSL globalement pour le développement
        'timeout' => 30,
        'retry' => [
            'times' => 3,
            'sleep' => 100,
        ],
    ],
];
    
