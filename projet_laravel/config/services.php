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
];