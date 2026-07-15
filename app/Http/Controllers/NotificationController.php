<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $this->visibleNotifications($request)
            ->latest()
            ->paginate(20);

        $notifications->getCollection()->transform(function (Notification $notification) use ($user) {
            $notification->is_read = $notification->user_id
                ? $notification->read_at !== null
                : $notification->reads()->where('user_id', $user->id)->exists();

            return $notification;
        });

        return response()->json([
            'unread_count' => $this->unreadCount($request),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse|JsonResponse
    {
        $this->authorizeNotification($request, $notification);

        if ($notification->user_id) {
            $notification->update(['read_at' => now()]);
        } else {
            NotificationRead::updateOrCreate(
                ['notification_id' => $notification->id, 'user_id' => $request->user()->id],
                ['read_at' => now()]
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'read']);
        }

        return back()->with('status', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $this->visibleNotifications($request)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->visibleNotifications($request)
            ->whereNull('user_id')
            ->whereDoesntHave('reads', fn ($query) => $query->where('user_id', $user->id))
            ->pluck('id')
            ->each(fn (int $notificationId) => NotificationRead::create([
                'notification_id' => $notificationId,
                'user_id' => $user->id,
                'read_at' => now(),
            ]));

        if ($request->wantsJson()) {
            return response()->json(['status' => 'all_read']);
        }

        return back()->with('status', 'All notifications marked as read.');
    }

    private function authorizeNotification(Request $request, Notification $notification): void
    {
        $user = $request->user();

        abort_unless(
            $notification->user_id === $user->id || in_array($notification->audience, ['all', $user->role], true),
            403
        );
    }

    private function visibleNotifications(Request $request)
    {
        $user = $request->user();

        return Notification::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('audience', ['all', $user->role]);
            });
    }

    private function unreadCount(Request $request): int
    {
        $user = $request->user();

        return $this->visibleNotifications($request)
            ->where(function ($query) use ($user) {
                $query->where(function ($personal) use ($user) {
                    $personal->where('user_id', $user->id)->whereNull('read_at');
                })->orWhere(function ($broadcast) use ($user) {
                    $broadcast->whereNull('user_id')
                        ->whereDoesntHave('reads', fn ($read) => $read->where('user_id', $user->id));
                });
            })
            ->count();
    }
}
