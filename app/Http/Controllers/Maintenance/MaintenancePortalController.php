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

    public function workOrders(Request $request): View
    {
        return view('maintenance.dashboard', [
            'workOrders' => $this->assignedOrders($request)->with(['complaint.flat', 'complaint.resident'])->latest()->paginate(15),
            'stats' => $this->staffStats($request),
            'filter' => 'all',
        ]);
    }

    public function inProgress(Request $request): View
    {
        return view('maintenance.dashboard', [
            'workOrders' => $this->assignedOrders($request)->where('status', 'in_progress')->with(['complaint.flat', 'complaint.resident'])->latest()->paginate(15),
            'stats' => $this->staffStats($request),
            'filter' => 'in_progress',
        ]);
    }

    public function completed(Request $request): View
    {
        return $this->history($request);
    }

    public function show(Request $request, string $order): View
    {
        $workOrder = $this->resolveAssignedOrder($request, $order);

        return view('maintenance.show', [
            'workOrder' => $workOrder->load(['complaint.flat.building', 'complaint.resident', 'complaint.messages.user', 'notes.user']),
        ]);
    }

    public function edit(Request $request, string $order): View
    {
        $workOrder = $this->resolveAssignedOrder($request, $order);

        abort_if($workOrder->status === 'completed', 404);

        return view('maintenance.update', [
            'workOrder' => $workOrder->load(['complaint.flat']),
        ]);
    }

    public function update(UpdateWorkOrderRequest $request, WorkOrder $order): RedirectResponse
    {
        $this->authorize('update', $order);

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

    public function profile(Request $request): View
    {
        return view('resident.profile', [
            'user' => $request->user(),
            'profile' => $request->user()->staffProfile,
        ]);
    }

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

    public function updatePassword(Request $request): RedirectResponse
    {
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

    private function assignedOrders(Request $request)
    {
        return WorkOrder::query()->where('assigned_to', $request->user()->id);
    }

    private function staffStats(Request $request): array
    {
        return [
            'todo' => $this->assignedOrders($request)->where('status', 'todo')->count(),
            'in_progress' => $this->assignedOrders($request)->where('status', 'in_progress')->count(),
            'completed' => $this->assignedOrders($request)->where('status', 'completed')->count(),
            'urgent' => $this->assignedOrders($request)->whereIn('priority', ['high', 'emergency', 'urgent'])->count(),
        ];
    }

    private function resolveAssignedOrder(Request $request, string $order): WorkOrder
    {
        $workOrder = WorkOrder::findOrFail($order);

        $this->ensureAssigned($request, $workOrder);

        return $workOrder;
    }

    private function ensureAssigned(Request $request, WorkOrder $order): void
    {
        $this->authorize('view', $order);
    }
}
