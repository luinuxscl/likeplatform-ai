<?php

declare(strict_types=1);

namespace LikePlatform\AI\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use LikePlatform\AI\Models\AIUsageLog;
use LikePlatform\Contracts\AI\AIUsageTrackerContract;

class AIStatsController extends Controller
{
    /**
     * Display AI usage statistics.
     */
    public function index(): View
    {
        $period = request('period', 'month');
        $tracker = app(AIUsageTrackerContract::class);
        $usage = $tracker->getUsage($period);

        $totalRequests = AIUsageLog::count();
        $totalCost = AIUsageLog::sum('cost');
        $totalTokens = AIUsageLog::sum('tokens');

        $recentLogs = AIUsageLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('likeplatform-ai::stats.index', compact(
            'usage',
            'totalRequests',
            'totalCost',
            'totalTokens',
            'recentLogs',
            'period'
        ));
    }
}
