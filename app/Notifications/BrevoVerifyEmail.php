<?php

namespace App\Notifications;

use App\Notifications\Channels\BrevoChannel;
use Illuminate\Auth\Notifications\VerifyEmail;

class BrevoVerifyEmail extends VerifyEmail
{
    /**
     * @return array<int, class-string>
     */
    public function via($notifiable): array
    {
        return [BrevoChannel::class];
    }

    /**
     * @return array{subject: string, html_content: string, tags: array<int, string>}
     */
    public function toBrevo(object $notifiable): array
    {
        $verificationUrl = htmlspecialchars(
            $this->verificationUrl($notifiable),
            ENT_QUOTES,
            'UTF-8',
        );
        $name = htmlspecialchars((string) ($notifiable->name ?? 'Resident'), ENT_QUOTES, 'UTF-8');
        $expiresIn = (int) config('auth.verification.expire', 60);

        return [
            'subject' => 'Verify your Nestora email address',
            'html_content' => <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <body style="margin:0;background:#f5f7fa;font-family:Arial,sans-serif;color:#1f2937;">
                    <div style="max-width:600px;margin:32px auto;background:#ffffff;border:1px solid #e5e7eb;padding:32px;">
                        <h1 style="margin-top:0;font-size:24px;">Welcome to Nestora</h1>
                        <p>Hello {$name},</p>
                        <p>Verify your email address to continue your resident registration.</p>
                        <p style="margin:28px 0;">
                            <a href="{$verificationUrl}" style="display:inline-block;background:#0f766e;color:#ffffff;padding:12px 20px;text-decoration:none;border-radius:6px;">
                                Verify email address
                            </a>
                        </p>
                        <p>This verification link expires in {$expiresIn} minutes.</p>
                        <p>If you did not create a Nestora account, you can ignore this email.</p>
                    </div>
                </body>
                </html>
                HTML,
            'tags' => ['email-verification'],
        ];
    }
}
