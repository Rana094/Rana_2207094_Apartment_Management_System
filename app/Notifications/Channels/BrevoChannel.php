<?php

namespace App\Notifications\Channels;

use App\Services\BrevoEmailService;
use Illuminate\Notifications\Notification;
use RuntimeException;

class BrevoChannel
{
    public function __construct(private readonly BrevoEmailService $brevo) {}

    public function send(object $notifiable, Notification $notification): ?string
    {
        if (! method_exists($notification, 'toBrevo')) {
            throw new RuntimeException(sprintf(
                'Notification %s must define a toBrevo method.',
                $notification::class,
            ));
        }

        /** @var array{subject: string, html_content: string, tags?: array<int, string>} $message */
        $message = $notification->toBrevo($notifiable);
        $email = $notifiable->routeNotificationFor('mail', $notification);

        if (! is_string($email) || $email === '') {
            return null;
        }

        return $this->brevo->send(
            recipientEmail: $email,
            recipientName: (string) ($notifiable->name ?? ''),
            subject: $message['subject'],
            htmlContent: $message['html_content'],
            tags: $message['tags'] ?? [],
        );
    }
}
