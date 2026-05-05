<?php

declare(strict_types=1);

namespace LikePlatform\AI\Providers;

use Illuminate\Support\ServiceProvider;
use LikePlatform\AI\Contracts\AIProvider;
use LikePlatform\AI\Contracts\AIUsageTracker;
use LikePlatform\Contracts\AI\AIProviderContract;
use LikePlatform\Contracts\AI\AIUsageTrackerContract;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ai.php', 'likeplatform-ai'
        );

        $this->app->singleton(AIProviderContract::class, AIProvider::class);
        $this->app->singleton(AIUsageTrackerContract::class, AIUsageTracker::class);
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
