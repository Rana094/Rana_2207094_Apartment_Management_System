<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return back()->with('status', "{$resident->name} has been rejected.");
    }
}
