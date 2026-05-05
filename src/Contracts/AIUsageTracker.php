<?php

declare(strict_types=1);

namespace LikePlatform\AI\Contracts;

use LikePlatform\AI\Models\AIUsageLog;
use LikePlatform\Contracts\AI\AIUsageTrackerContract;

/**
 * Implementation of AI usage tracker.
 *
 * Records AI completions and provides aggregated usage statistics
 * broken down by period (day, week, month, year).
 */
final readonly class AIUsageTracker implements AIUsageTrackerContract
{
    /**
     * Record a usage event.
     *
     * @param string $provider Provider name
     * @param string $model Model identifier
     * @param int $tokens Total tokens consumed
     * @param float $cost Estimated cost in USD
     */
    public function track(string $provider, string $model, int $tokens, float $cost): void
    {
        AIUsageLog::create([
            'user_id' => auth()->id(),
            'provider' => $provider,
            'model' => $model,
            'tokens' => $tokens,
            'cost' => $cost,
        ]);
    }

    /**
     * Get aggregated usage for a given period.
     *
     * @param string $period One of: 'day', 'week', 'month', 'year'
     * @return array<string, mixed> Usage statistics
     */
    public function getUsage(string $period): array
    {
        $start = match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        return AIUsageLog::where('created_at', '>=', $start)
            ->selectRaw('provider, model, COUNT(*) as requests, SUM(tokens) as total_tokens, SUM(cost) as total_cost')
            ->groupBy('provider', 'model')
            ->get()
            ->toArray();
    }
}
