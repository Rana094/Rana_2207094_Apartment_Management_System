<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ResidentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\NotificationService;

class ResidentApprovalController extends Controller
{
    /**
     * List resident signup requests waiting for manager approval.
     */
    public function index(): View
    {
        return view('manager.resident-approvals.index', [
            'residents' => User::query()
                ->with('requestedFlat.building')
                ->where('role', 'resident')
                ->whereIn('status', ['pending_verification', 'pending_approval'])
                ->latest()
                ->paginate(15),
        ]);
    }

    /**
     * Approve a resident and convert their requested flat into an active assignment.
     */
    public function approve(Request $request, User $resident): RedirectResponse
    {
        abort_unless($resident->role === 'resident', 404);

        $flat = $resident->requestedFlat()->first();

        // Approval is blocked if the requested flat disappeared or was already taken.
        abort_unless($flat !== null, 422, 'This resident did not select a valid flat.');
        $flat->loadMissing('building');
        abort_unless($flat->status === 'vacant', 422, 'The selected flat is no longer vacant.');
        abort_if(
            ResidentProfile::where('flat_id', $flat->id)
                ->where('status', 'active')
                ->where('user_id', '!=', $resident->id)
                ->exists(),
            422,
            'The selected flat is already assigned to another resident.'
        );

        $resident->forceFill([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'rejection_reason' => null,
            'flat_info' => trim(($flat->building?->name ? $flat->building->name.', ' : '').'Flat '.$flat->flat_number),
        ])->save();

        // ResidentProfile is what connects an approved resident to their active flat.
        ResidentProfile::updateOrCreate(
            ['user_id' => $resident->id],
            [
                'flat_id' => $flat->id,
                'resident_type' => $resident->resident_type ?? 'tenant',
                'move_in_date' => now()->toDateString(),
                'status' => 'active',
            ]
        );

        // Once approved, the flat is no longer available for signup.
        $flat->update([
            'status' => 'occupied',
            'type' => $resident->resident_type ?? $flat->type,
        ]);

        app(NotificationService::class)->toUser(
            $resident->id,
            'resident_approved',
            'Account approved',
            'Your resident account has been approved. You can now access your portal.',
            route('resident.dashboard', absolute: false)
        );

        return back()->with('status', "{$resident->name} has been approved.");
    }

    /**
     * Reject a resident signup and release the requested flat for others.
     */
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
            'requested_flat_id' => null,
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
