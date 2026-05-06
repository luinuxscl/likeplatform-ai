<?php

declare(strict_types=1);

namespace LikePlatform\AI\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitAIRequests
{
    /**
     * Handle an incoming request.
     *
     * Limits AI completion requests per user within a time window.
     */
    public function handle(Request $request, Closure $next, int $maxRequests = 60, int $perMinutes = 1): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $recentCount = \LikePlatform\AI\Models\AIUsageLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subMinutes($perMinutes))
            ->count();

        if ($recentCount >= $maxRequests) {
            return response()->json([
                'message' => __('likeplatform-ai::stats.rate_limit_exceeded', [
                    'max' => $maxRequests,
                    'minutes' => $perMinutes,
                ]),
            ], 429);
        }

        return $next($request);
    }
}
