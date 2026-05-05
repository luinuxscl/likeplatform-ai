<?php

declare(strict_types=1);

namespace LikePlatform\AI\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the LikePlatform AI package.
 *
 * Wraps Laravel 13's native AI SDK to provide LikePlatform-specific
 * features: prompt templates, usage tracking, and cost reporting.
 */
class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ai.php', 'likeplatform-ai'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/ai.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'likeplatform-ai');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/ai.php' => config_path('likeplatform-ai.php'),
            ], 'likeplatform-ai-config');
        }
    }
}
