<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | This package wraps Laravel 13's native AI SDK. Configure providers
    | and models via Laravel's built-in AI configuration.
    |
    | LikePlatform-specific settings (templates, usage tracking) are below.
    |
    */

    // Default AI provider (openai, anthropic, gemini)
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),

    // Default model for completions
    'default_model' => env('AI_DEFAULT_MODEL', 'gpt-4o-mini'),

    // Maximum tokens per completion
    'max_tokens' => 4096,

    // Default temperature
    'temperature' => 0.7,

    // Enable usage tracking in database
    'track_usage' => true,
];
