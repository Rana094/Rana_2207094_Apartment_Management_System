<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\UpdateWorkOrderRequest;
use App\Models\WorkOrder;
use App\Models\WorkOrderNote;
use App\Services\FileUploadService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MaintenancePortalController extends Controller
{
    /**
     * Show staff dashboard with only work orders assigned to the logged-in staff member.
     */
    public function dashboard(Request $request): View
    {
        $workOrders = $this->assignedOrders($request)
            ->with(['complaint.flat.building', 'complaint.resident'])
            ->latest()
            ->get();

        return view('maintenance.dashboard', [
            'workOrders' => $workOrders,
            'stats' => [
                'todo' => $workOrders->where('status', 'todo')->count(),
                'in_progress' => $workOrders->where('status', 'in_progress')->count(),
                'completed' => $workOrders->where('status', 'completed')->count(),
                'urgent' => $workOrders->whereIn('priority', ['high', 'emergency', 'urgent'])->count(),
            ],
            'recentHistory' => $this->assignedOrders($request)->where('status', 'completed')->latest('completed_at')->take(5)->get(),
        ]);
    }

    /**
     * Show all assigned work orders with pagination.
     */
    public function workOrders(Request $request): View
    {
        return view('maintenance.dashboard', [
            'workOrders' => $this->assignedOrders($request)->with(['complaint.flat', 'complaint.resident'])->latest()->paginate(15),
            'stats' => $this->staffStats($request),
            'filter' => 'all',
        ]);
    }

    /**
     * Show only assigned work orders currently in progress.
     */
    public function inProgress(Request $request): View
    {
        return view('maintenance.dashboard', [
            'workOrders' => $this->assignedOrders($request)->where('status', 'in_progress')->with(['complaint.flat', 'complaint.resident'])->latest()->paginate(15),
            'stats' => $this->staffStats($request),
            'filter' => 'in_progress',
        ]);
    }

    /**
     * Reuse the repair history page for completed work-order menu links.
     */
    public function completed(Request $request): View
    {
        return $this->history($request);
    }

    /**
     * Show one real assigned work order, including resident messages and staff repair notes.
     */
    public function show(Request $request, string $order): View
    {
        $workOrder = $this->resolveAssignedOrder($request, $order);

        return view('maintenance.show', [
            'workOrder' => $workOrder->load(['complaint.flat.building', 'complaint.resident', 'complaint.messages.user', 'notes.user']),
        ]);
    }

    /**
     * Show the status update form for an unfinished assigned work order.
     */
    public function edit(Request $request, string $order): View
    {
        $workOrder = $this->resolveAssignedOrder($request, $order);

        // Completed work orders are historical records and should not be edited.
        abort_if($workOrder->status === 'completed', 404);

        return view('maintenance.update', [
            'workOrder' => $workOrder->load(['complaint.flat']),
        ]);
    }

    /**
     * Store a staff progress note and synchronize the linked complaint status.
     */
    public function update(UpdateWorkOrderRequest $request, WorkOrder $order): RedirectResponse
    {
        $this->authorize('update', $order);

        // Accept common status wording from the UI but store consistent database values.
        $statusMap = [
            'resolved' => 'completed',
            'complete' => 'completed',
            'completed' => 'completed',
            'in_progress' => 'in_progress',
            'todo' => 'todo',
            'urgent' => 'urgent',
        ];

        $validated = $request->validated();

        $status = $statusMap[$validated['status']] ?? $validated['status'];
        abort_unless(in_array($status, ['todo', 'in_progress', 'completed', 'urgent'], true), 422);

        $proof = $request->file('completion_photo') ?? $request->file('completion_proof');

        // Notes are the repair update history visible to staff and residents.
        WorkOrderNote::create([
            'work_order_id' => $order->id,
            'user_id' => $request->user()->id,
            'status' => $status,
            'remarks' => $validated['remarks'],
            'proof_path' => $proof ? app(FileUploadService::class)->store($proof, 'work-order-proofs') : null,
            'noted_at' => now(),
        ]);

        $order->update([
            'status' => $status,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        if ($order->complaint) {
            // Resident complaint status follows the maintenance work-order progress.
            $order->complaint->update([
                'status' => $status === 'completed' ? 'resolved' : 'in_progress',
            ]);

            app(NotificationService::class)->toUser(
                $order->complaint->resident_id,
                'work_order_'.$status,
                'Maintenance task updated',
                $order->title.' is now '.$status.'.',
                route('resident.complaints.show', $order->complaint, absolute: false)
            );
        }

        if ($order->assigned_by) {
            app(NotificationService::class)->toUser(
                $order->assigned_by,
                'work_order_'.$status,
                'Work order updated',
                $order->title.' was updated by maintenance staff.',
                route('manager.complaints.index', absolute: false)
            );
        }

        return redirect()->route('maintenance.show', $order)->with('status', 'Work order updated.');
    }

    /**
     * Show completed repair history and all notes submitted by this staff member.
     */
    public function history(Request $request): View
    {
        return view('maintenance.history', [
            'completedOrders' => $this->assignedOrders($request)
                ->with(['complaint.flat', 'notes'])
                ->where('status', 'completed')
                ->latest('completed_at')
                ->paginate(15),
            'notes' => WorkOrderNote::with('workOrder.complaint')
                ->where('user_id', $request->user()->id)
                ->latest('noted_at')
                ->paginate(15),
        ]);
    }

    /**
     * Reuse the shared profile view for maintenance staff account settings.
     */
    public function profile(Request $request): View
    {
        return view('resident.profile', [
            'user' => $request->user(),
            'profile' => $request->user()->staffProfile,
        ]);
    }

    /**
     * Update basic staff profile fields.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($validated);

        return redirect()->route('maintenance.profile')->with('status', 'Profile updated.');
    }

    /**
     * Update staff password after confirming the current password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Some profile forms submit new_password; normalize it to Laravel's confirmed rule names.
        if ($request->filled('new_password') && ! $request->filled('password')) {
            $request->merge([
                'password' => $request->input('new_password'),
                'password_confirmation' => $request->input('new_password_confirmation'),
            ]);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('maintenance.profile')->with('status', 'Password updated.');
    }

    /**
     * Base query for all work orders assigned to the current staff user.
     */
    private function assignedOrders(Request $request)
    {
        return WorkOrder::query()->where('assigned_to', $request->user()->id);
    }

    /**
     * Count work orders by status for dashboard stat cards.
     */
    private function staffStats(Request $request): array
    {
        return [
            'todo' => $this->assignedOrders($request)->where('status', 'todo')->count(),
            'in_progress' => $this->assignedOrders($request)->where('status', 'in_progress')->count(),
            'completed' => $this->assignedOrders($request)->where('status', 'completed')->count(),
            'urgent' => $this->assignedOrders($request)->whereIn('priority', ['high', 'emergency', 'urgent'])->count(),
        ];
    }

    /**
     * Resolve a work order ID and enforce that the current staff member can view it.
     */
    private function resolveAssignedOrder(Request $request, string $order): WorkOrder
    {
        $workOrder = WorkOrder::findOrFail($order);

        $this->ensureAssigned($request, $workOrder);

        return $workOrder;
    }

    /**
     * Delegate view permission to the WorkOrderPolicy.
     */
    private function ensureAssigned(Request $request, WorkOrder $order): void
    {
        $this->authorize('view', $order);
    }
}
