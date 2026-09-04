<?php

return [
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-3.6-flash'),
        'base_url' => env(
            'GEMINI_BASE_URL',
            'https://generativelanguage.googleapis.com/v1beta'
        ),
        'timeout' => (int) env('GEMINI_TIMEOUT', 30),
    ],
];
