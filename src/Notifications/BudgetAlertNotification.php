<?php

declare(strict_types=1);

namespace LikePlatform\AI\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetAlertNotification extends Notification
{
    public function __construct(
        public readonly float $totalCost,
        public readonly float $threshold,
        public readonly string $period,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $percent = round(($this->totalCost / $this->threshold) * 100, 1);

        return (new MailMessage)
            ->subject("AI Budget Alert: {$percent}% of {$this->period}ly budget used")
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin'))
            ->line("AI usage this {$this->period}: \${$this->totalCost} of \${$this->threshold} ({$percent}%).")
            ->line($this->totalCost >= $this->threshold
                ? 'Your AI budget threshold has been exceeded.'
                : 'You are approaching your AI budget limit.')
            ->action('View AI Statistics', url('/ai/stats'))
            ->line('Consider reviewing usage or adjusting the budget.');
    }
}
