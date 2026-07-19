<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Return notifications visible to the logged-in user with read/unread state.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $this->visibleNotifications($request)
            ->latest()
            ->paginate(20);

        // Personal notifications store read_at directly; broadcast notifications use notification_reads.
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

    /**
     * Mark one personal or broadcast notification as read.
     */
    public function markRead(Request $request, Notification $notification): RedirectResponse|JsonResponse
    {
        $this->authorizeNotification($request, $notification);

        if ($notification->user_id) {
            $notification->update(['read_at' => now()]);
        } else {
            // Broadcast notifications need a per-user read row.
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

    /**
     * Mark every currently visible notification as read for this user.
     */
    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        // Personal notifications can be updated directly on the notifications table.
        $this->visibleNotifications($request)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Broadcast notifications must create read rows for this specific user.
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

    /**
     * Ensure a user can only interact with their own or role-wide notifications.
     */
    private function authorizeNotification(Request $request, Notification $notification): void
    {
        $user = $request->user();

        abort_unless(
            $notification->user_id === $user->id || in_array($notification->audience, ['all', $user->role], true),
            403
        );
    }

    /**
     * Base query for personal, all-user, and role-specific notifications.
     */
    private function visibleNotifications(Request $request)
    {
        $user = $request->user();

        return Notification::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('audience', ['all', $user->role]);
            });
    }

    /**
     * Count unread personal notifications plus broadcast notifications without a read row.
     */
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
