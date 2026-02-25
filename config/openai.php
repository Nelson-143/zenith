<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 500),
    'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    
    'tier_limits' => [
        'basic' => env('BASIC_MONTHLY_TOKENS', 50000),
        'pro' => env('PRO_MONTHLY_TOKENS', 200000),
        'enterprise' => env('ENTERPRISE_MONTHLY_TOKENS', -1),
    ]
];