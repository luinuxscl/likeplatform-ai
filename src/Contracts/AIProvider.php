<?php

declare(strict_types=1);

namespace LikePlatform\AI\Contracts;

use LikePlatform\AI\Models\AIUsageLog;
use LikePlatform\Contracts\AI\AIProviderContract;

/**
 * Implementation of AI provider.
 *
 * Wraps Laravel 13's native AI SDK to provide LikePlatform-specific
 * AI completions with usage tracking.
 */
final readonly class AIProvider implements AIProviderContract
{
    /**
     * Pricing per 1K tokens for common models (USD).
     *
     * @var array<string, array<string, float>>
     */
    private const PRICING = [
        'openai' => [
            'gpt-4o' => ['input' => 0.0025, 'output' => 0.01],
            'gpt-4o-mini' => ['input' => 0.00015, 'output' => 0.0006],
        ],
        'anthropic' => [
            'claude-3-opus' => ['input' => 0.015, 'output' => 0.075],
            'claude-3-sonnet' => ['input' => 0.003, 'output' => 0.015],
            'claude-3-haiku' => ['input' => 0.00025, 'output' => 0.00125],
        ],
    ];

    /**
     * Generate a text completion.
     *
     * Delegates to Laravel 13's native AI SDK.
     *
     * @param string $prompt The input prompt
     * @param array<string, mixed> $options Provider-specific options
     * @return string The generated completion text
     */
    public function complete(string $prompt, array $options = []): string
    {
        $start = microtime(true);
        $model = $options['model'] ?? 'gpt-4o-mini';
        $provider = $this->getProviderName();

        // Delegate to Laravel's native AI SDK
        if (function_exists('AI')) {
            $response = \AI::provider($provider)->complete($prompt, $options);
        } else {
            $response = 'AI SDK not configured. Please configure Laravel AI SDK.';
        }

        // Track usage
        $inputTokens = strlen($prompt) / 4;
        $outputTokens = strlen($response) / 4;
        $tokens = (int) round($inputTokens + $outputTokens);
        $cost = $this->calculateCost($model, (int) $inputTokens, (int) $outputTokens);
        $duration = (int) ((microtime(true) - $start) * 1000);

        AIUsageLog::create([
            'user_id' => auth()->id(),
            'provider' => $provider,
            'model' => $model,
            'prompt' => $prompt,
            'tokens' => $tokens,
            'input_tokens' => (int) $inputTokens,
            'output_tokens' => (int) $outputTokens,
            'cost' => $cost,
            'duration_ms' => $duration,
        ]);

        return $response;
    }

    /**
     * Get available models for this provider.
     *
     * @return array<string> List of model identifiers
     */
    public function getModels(): array
    {
        $provider = $this->getProviderName();

        return array_keys(self::PRICING[$provider] ?? []);
    }

    /**
     * Calculate estimated cost for a completion.
     *
     * @param string $model Model identifier
     * @param int $inputTokens Number of input tokens
     * @param int $outputTokens Number of output tokens
     */
    public function calculateCost(string $model, int $inputTokens, int $outputTokens): float
    {
        $provider = $this->getProviderName();
        $pricing = self::PRICING[$provider][$model] ?? null;

        if (!$pricing) {
            return 0.0;
        }

        return ($inputTokens / 1000) * $pricing['input'] + ($outputTokens / 1000) * $pricing['output'];
    }

    /**
     * Get the provider name.
     */
    public function getProviderName(): string
    {
        return config('likeplatform-ai.default_provider', 'openai');
    }
}
