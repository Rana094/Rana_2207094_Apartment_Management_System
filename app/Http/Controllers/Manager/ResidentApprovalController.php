<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\NotificationService;

class ResidentApprovalController extends Controller
{
    public function index(): View
    {
        return view('manager.resident-approvals.index', [
            'residents' => User::query()
                ->where('role', 'resident')
                ->whereIn('status', ['pending_verification', 'pending_approval'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function approve(Request $request, User $resident): RedirectResponse
    {
        abort_unless($resident->role === 'resident', 404);

        $resident->forceFill([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'rejection_reason' => null,
        ])->save();

        app(NotificationService::class)->toUser(
            $resident->id,
            'resident_approved',
            'Account approved',
            'Your resident account has been approved. You can now access your portal.',
            route('resident.dashboard', absolute: false)
        );

        return back()->with('status', "{$resident->name} has been approved.");
    }

    public function reject(Request $request, User $resident): RedirectResponse
    {
        abort_unless($resident->role === 'resident', 404);

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $resident->forceFill([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => $request->user()->id,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ])->save();

        app(NotificationService::class)->toUser(
            $resident->id,
            'resident_rejected',
            'Account rejected',
            $validated['rejection_reason'] ?? 'Your resident registration was rejected by management.',
            route('approval.pending', absolute: false)
        );

        return back()->with('status', "{$resident->name} has been rejected.");
    }
}
