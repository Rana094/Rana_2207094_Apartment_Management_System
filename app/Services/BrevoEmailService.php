<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BrevoEmailService
{
    private const API_URL = 'https://api.brevo.com/v3';

    /**
     * @param  array<int, string>  $tags
     */
    public function send(
        string $recipientEmail,
        string $recipientName,
        string $subject,
        string $htmlContent,
        array $tags = [],
    ): string {
        $fromEmail = config('services.brevo.from_email');
        $fromName = (string) config('services.brevo.from_name', 'Nestora');

        if (! is_string($fromEmail) || $fromEmail === '') {
            throw new RuntimeException('BREVO_FROM_EMAIL is not configured.');
        }

        $response = $this->client()->post('/smtp/email', [
            'sender' => [
                'email' => $fromEmail,
                'name' => $fromName,
            ],
            'to' => [[
                'email' => $recipientEmail,
                'name' => $recipientName,
            ]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'tags' => $tags,
        ]);

        $response->throw();

        $messageId = $response->json('messageId');

        if (! is_string($messageId) || $messageId === '') {
            throw new RuntimeException('Brevo did not return a message ID.');
        }

        return $messageId;
    }

    private function client(): PendingRequest
    {
        $apiKey = config('services.brevo.api_key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw new RuntimeException('BREVO_API_KEY is not configured.');
        }

        return Http::baseUrl(self::API_URL)
            ->acceptJson()
            ->asJson()
            ->withHeaders(['api-key' => $apiKey])
            ->timeout(15)
            ->retry(2, 500);
    }
}
