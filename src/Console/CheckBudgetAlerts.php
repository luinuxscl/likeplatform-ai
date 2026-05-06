<?php

declare(strict_types=1);

namespace LikePlatform\AI\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use LikePlatform\AI\Models\AIUsageLog;

class CheckBudgetAlerts extends Command
{
    protected $signature = 'likeplatform:ai-check-budget
                            {--threshold=50.00 : Budget threshold in dollars (default $50)}
                            {--period=month : Period to check (day, week, month)}';

    protected $description = 'Check AI usage against budget thresholds and send alerts';

    public function handle(): int
    {
        $threshold = (float) $this->option('threshold');
        $period = $this->option('period');

        $startDate = match ($period) {
            'day' => Carbon::now()->startOfDay(),
            'week' => Carbon::now()->startOfWeek(),
            default => Carbon::now()->startOfMonth(),
        };

        $totalCost = AIUsageLog::where('created_at', '>=', $startDate)
            ->sum('cost');

        $usagePercent = $threshold > 0 ? round(($totalCost / $threshold) * 100, 1) : 0;

        $this->info("AI usage this {$period}: \${$totalCost} / \${$threshold} ({$usagePercent}%)");

        if ($totalCost >= $threshold) {
            $this->warn("Budget threshold exceeded!");

            $this->notifyAdmins($totalCost, $threshold, $period);
        } elseif ($usagePercent >= 80) {
            $this->warn("Approaching budget limit ({$usagePercent}%)");

            $this->notifyAdmins($totalCost, $threshold, $period);
        }

        return self::SUCCESS;
    }

    private function notifyAdmins(float $totalCost, float $threshold, string $period): void
    {
        $users = \App\Models\User::role('admin')->get();

        foreach ($users as $user) {
            $user->notify(new \LikePlatform\AI\Notifications\BudgetAlertNotification(
                $totalCost,
                $threshold,
                $period
            ));
        }

        $this->info("Notified {$users->count()} admin(s).");
    }
}
