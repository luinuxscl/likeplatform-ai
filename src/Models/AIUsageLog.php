<?php

declare(strict_types=1);

namespace LikePlatform\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * AI usage log entry.
 *
 * Records each AI completion with provider, model,
 * token count, and cost for tracking and reporting.
 */
class AIUsageLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'model',
        'prompt',
        'tokens',
        'input_tokens',
        'output_tokens',
        'cost',
        'duration_ms',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tokens' => 'integer',
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
            'cost' => 'float',
            'duration_ms' => 'integer',
        ];
    }

    /**
     * Get the user that made this AI request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
