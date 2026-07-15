<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function toUser(?int $userId, string $type, string $title, ?string $body = null, ?string $actionUrl = null): ?Notification
    {
        if (! $userId) {
            return null;
        }

        return Notification::create([
            'user_id' => $userId,
            'audience' => 'user',
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
        ]);
    }

    public function toRole(string $role, string $type, string $title, ?string $body = null, ?string $actionUrl = null): void
    {
        User::query()
            ->where('role', $role)
            ->where('status', 'approved')
            ->pluck('id')
            ->each(fn (int $userId) => $this->toUser($userId, $type, $title, $body, $actionUrl));
    }

    public function toAll(string $type, string $title, ?string $body = null, ?string $actionUrl = null): Notification
    {
        return Notification::create([
            'audience' => 'all',
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
        ]);
    }
}
