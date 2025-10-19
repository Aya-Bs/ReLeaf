<?php

return [
    'disk' => env('FLYER_DISK', 'public'),
    'base_path' => env('FLYER_BASE_PATH', 'flyers'),
    'save_to_public' => (bool) env('FLYER_SAVE_TO_PUBLIC', true),

    'template' => env('FLYER_TEMPLATE', 'flyers.default'),

    'image' => [
        'provider' => env('FLYER_IMAGE_PROVIDER', 'cloudflare'),
        'cloudflare' => [
            'account_id' => env('CF_ACCOUNT_ID'),
            'api_token' => env('CF_AI_TOKEN'),
            // Example model slug, confirm in Cloudflare dashboard (Workers AI Marketplace)
            // e.g. '@leonardo/lucid-origin' or '@cf/black-forest-labs/flux-1-schnell'
            'model' => env('CF_AI_MODEL', '@cf/leonardo/lucid-origin'),
            'width' => (int) env('FLYER_IMG_WIDTH', 1792),
            'height' => (int) env('FLYER_IMG_HEIGHT', 1024),
        ],
    ],

    'text' => [
        'provider' => env('FLYER_TEXT_PROVIDER', 'gemini'),
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
        ],
    ],
];
