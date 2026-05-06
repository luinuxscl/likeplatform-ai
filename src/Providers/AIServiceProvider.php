<?php

declare(strict_types=1);

namespace LikePlatform\AI\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use LikePlatform\AI\Console\CheckBudgetAlerts;
use LikePlatform\AI\Contracts\AIProvider;
use LikePlatform\AI\Contracts\AIUsageTracker;
use LikePlatform\AI\Http\Middleware\RateLimitAIRequests;
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
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'likeplatform-ai');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'likeplatform-ai');

        $router = $this->app->make('router');
        $router->aliasMiddleware('ai.ratelimit', RateLimitAIRequests::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckBudgetAlerts::class,
            ]);

            $this->publishes([
                __DIR__.'/../../config/ai.php' => config_path('likeplatform-ai.php'),
            ], 'likeplatform-ai-config');
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('likeplatform:ai-check-budget')
                ->dailyAt('09:00')
                ->withoutOverlapping();
        });
    }
}
