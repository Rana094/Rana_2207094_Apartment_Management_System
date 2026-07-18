<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Requests\Manager\StoreBillRequest;
use App\Models\Bill;
use App\Models\Building;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\EmergencyRequest;
use App\Models\FacilityBooking;
use App\Models\Flat;
use App\Models\Notice;
use App\Models\PaymentProof;
use App\Models\ResidentProfile;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\VisitorRequest;
use App\Models\WorkOrder;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ManagerPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('manager.dashboard', [
            'stats' => [
                'residents' => User::where('role', 'resident')->whereIn('status', ['approved', 'suspended'])->count(),
                'occupied_flats' => Flat::where('status', 'occupied')->count(),
                'vacant_flats' => Flat::where('status', 'vacant')->count(),
                'available_flats' => Flat::availableForSignup()->count(),
                'reserved_flats' => Flat::where('status', 'vacant')
                    ->whereHas('pendingResidentRequests')
                    ->count(),
                'pending_approvals' => User::where('role', 'resident')->whereIn('status', ['pending_verification', 'pending_approval'])->count(),
                'unpaid_bills' => Bill::whereIn('status', ['unpaid', 'overdue'])->count(),
                'open_complaints' => Complaint::whereIn('status', ['open', 'in_progress'])->count(),
                'today_visitors' => VisitorRequest::whereDate('visit_date', today())->count(),
                'revenue' => Bill::where('status', 'paid')->sum('amount'),
            ],
            'pendingResidents' => User::where('role', 'resident')
                ->with('requestedFlat.building')
                ->whereIn('status', ['pending_verification', 'pending_approval'])
                ->latest()
                ->limit(5)
                ->get(),
            'urgentComplaints' => Complaint::with(['resident', 'flat'])
                ->whereIn('status', ['open', 'in_progress'])
                ->whereIn('priority', ['high', 'urgent', 'emergency'])
                ->latest()
                ->limit(5)
                ->get(),
            'activeEmergencies' => EmergencyRequest::with(['resident', 'flat'])
                ->whereIn('status', ['open', 'in_progress'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }

    public function residents(): View
    {
        return view('manager.residents.index', [
            'residents' => User::with('residentProfile.flat.building')
                ->where('role', 'resident')
                ->whereIn('status', ['approved', 'suspended'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function resident(string $resident): View
    {
        $residentModel = User::with(['residentProfile.flat.building', 'documents', 'bills', 'complaints'])
            ->where('role', 'resident')
            ->findOrFail($resident);

        $residentModel->load(['residentProfile.flatMembers', 'residentProfile.vehicleRegistrations']);

        return view('manager.residents.show', ['resident' => $residentModel]);
    }

    public function documents(): View
    {
        $registrationDocuments = User::query()
            ->where('role', 'resident')
            ->whereNotNull('document_path')
            ->latest()
            ->paginate(15, ['*'], 'registrations_page');

        $registrationDocuments->getCollection()->each(function (User $resident) {
            $resident->file_available = Storage::disk('private_uploads')->exists($resident->document_path);
        });

        $residentDocuments = Document::with(['user', 'flat.building'])
            ->latest()
            ->paginate(15, ['*'], 'documents_page');

        $residentDocuments->getCollection()->each(function (Document $document) {
            $document->file_available = Storage::disk('private_uploads')->exists($document->file_path);
        });

        return view('manager.documents.index', [
            'registrationDocuments' => $registrationDocuments,
            'residentDocuments' => $residentDocuments,
        ]);
    }

    public function updateResidentStatus(Request $request, User $resident): RedirectResponse
    {
        abort_unless($resident->role === 'resident', 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'suspended'])],
        ]);

        $resident->update([
            'status' => $validated['status'],
            'approved_at' => $validated['status'] === 'approved' ? ($resident->approved_at ?? now()) : $resident->approved_at,
        ]);

        $this->notify(
            $resident->id,
            'resident_status_updated',
            'Account '.$validated['status'],
            'Your Nestora resident account has been '.$validated['status'].'.'
        );

        return redirect()->route('manager.residents.index')->with('status', 'Resident account '.$validated['status'].'.');
    }

    public function flats(): View
    {
        return view('manager.flats.index', [
            'flats' => Flat::with(['building', 'residentProfiles.user', 'pendingResidentRequests'])->orderBy('flat_number')->paginate(20),
        ]);
    }

    public function createFlat(): View
    {
        return view('manager.flats.form', [
            'buildings' => Building::orderBy('name')->get(),
        ]);
    }

    public function storeFlat(Request $request): RedirectResponse
    {
        $data = $this->validateFlat($request);
        Flat::create($data);

        return redirect()->route('manager.flats.index')->with('status', 'Flat created.');
    }

    public function editFlat(Flat $flat): View
    {
        $flat->load('building');

        return view('manager.flats.form', [
            'flat' => $flat,
            'buildings' => Building::orderBy('name')->get(),
        ]);
    }

    public function updateFlat(Request $request, Flat $flat): RedirectResponse
    {
        $flat->update($this->validateFlat($request));

        return redirect()->route('manager.flats.index')->with('status', 'Flat updated.');
    }

    public function generateBill(): View
    {
        return view('manager.bills.generate', [
            'flats' => Flat::with('residentProfiles.user')->orderBy('flat_number')->get(),
        ]);
    }

    public function storeBill(StoreBillRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $query = ResidentProfile::with('user')
            ->whereHas('user', fn ($q) => $q->where('status', 'approved'));

        if ($request->filled('target_flat_id') && ! $request->boolean('bulk_billing')) {
            $query->where('flat_id', $validated['target_flat_id']);
        }

        $created = 0;
        foreach ($query->get() as $profile) {
            $bill = Bill::updateOrCreate(
                [
                    'resident_id' => $profile->user_id,
                    'billing_month' => $validated['period'].'-01',
                    'type' => $validated['category'],
                ],
                [
                    'flat_id' => $profile->flat_id,
                    'bill_number' => 'BILL-'.strtoupper(Str::random(8)),
                    'amount' => $validated['amount'],
                    'due_date' => $validated['due_date'],
                    'status' => 'unpaid',
                ]
            );
            PaymentGatewayController::sessionForBill($bill);
            $this->notify($profile->user_id, 'bill_created', 'New bill generated', 'A new bill is available in your resident portal.');
            $created++;
        }

        return redirect()->route('manager.bills.index')->with('status', "{$created} bill(s) generated.");
    }

    public function bills(): View
    {
        return view('manager.bills.index', [
            'bills' => Bill::with(['resident', 'flat', 'paymentProofs', 'latestPaymentTransaction'])->latest('due_date')->paginate(20),
        ]);
    }

    public function payments(): View
    {
        return view('manager.payments.index', [
            'paymentProofs' => PaymentProof::with(['bill.resident', 'user'])->latest()->paginate(20),
        ]);
    }

    public function payment(PaymentProof $payment): View
    {
        $payment->load(['bill.resident', 'user']);

        return view('manager.payments.show', ['paymentProof' => $payment]);
    }

    public function verifyPayment(Request $request, PaymentProof $paymentProof): RedirectResponse
    {
        $this->authorize('verify', $paymentProof);

        $status = $request->input('status', 'approved');
        abort_unless(in_array($status, ['approved', 'rejected'], true), 422);

        $paymentProof->update([
            'status' => $status,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        $paymentProof->bill->update([
            'status' => $status === 'approved' ? 'paid' : 'unpaid',
            'paid_at' => $status === 'approved' ? now() : null,
        ]);

        $this->notify($paymentProof->user_id, 'payment_'.$status, 'Payment '.$status, 'Your payment proof has been '.$status.'.');

        return redirect()->route('manager.payments.index')->with('status', 'Payment proof '.$status.'.');
    }

    public function reports(): View
    {
        return view('manager.reports.financial', [
            'summary' => [
                'total_billed' => Bill::sum('amount'),
                'total_paid' => Bill::where('status', 'paid')->sum('amount'),
                'total_unpaid' => Bill::whereIn('status', ['unpaid', 'overdue', 'pending_verification'])->sum('amount'),
                'pending_payment_proofs' => PaymentProof::where('status', 'pending')->count(),
            ],
            'billingBreakdown' => Bill::query()
                ->selectRaw('type, SUM(amount) as invoiced, SUM(CASE WHEN status = ? THEN amount ELSE 0 END) as collected', ['paid'])
                ->groupBy('type')
                ->orderBy('type')
                ->get(),
            'recentPayments' => PaymentProof::with(['bill.flat', 'user'])
                ->where('status', 'approved')
                ->latest('verified_at')
                ->limit(10)
                ->get(),
        ]);
    }

    public function complaints(): View
    {
        return view('manager.complaints.index', [
            'complaints' => Complaint::with(['resident', 'flat', 'workOrders.assignedStaff'])->latest()->paginate(20),
        ]);
    }

    public function assignComplaint(Complaint $complaint): View
    {
        $complaint->load(['resident', 'flat']);

        return view('manager.complaints.assign', [
            'complaint' => $complaint,
            'staff' => User::with('staffProfile')->whereIn('role', ['staff'])->where('status', 'approved')->get(),
        ]);
    }

    public function storeWorkOrder(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('assign', $complaint);

        $validated = $request->validate([
            'technician_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'staff')
                    ->where('status', 'approved')),
            ],
            'urgency' => ['required', Rule::in(['low', 'medium', 'high', 'urgent', 'emergency'])],
            'deadline' => ['nullable', 'date'],
            'instructions' => ['nullable', 'string', 'max:2000'],
        ]);

        WorkOrder::create([
            'complaint_id' => $complaint->id,
            'assigned_to' => $validated['technician_id'],
            'assigned_by' => $request->user()->id,
            'title' => 'Work order for '.$complaint->title,
            'instructions' => $validated['instructions'] ?? null,
            'priority' => $validated['urgency'] === 'urgent' ? 'high' : $validated['urgency'],
            'status' => 'todo',
            'due_at' => $validated['deadline'] ?? null,
        ]);

        $complaint->update(['status' => 'in_progress']);
        $this->notify($validated['technician_id'], 'work_order_assigned', 'New work order assigned', $complaint->title);
        $this->notify($complaint->resident_id, 'complaint_assigned', 'Complaint assigned', 'A technician has been assigned to your complaint.');

        return redirect()->route('manager.complaints.index')->with('status', 'Work order assigned.');
    }

    public function staff(): View
    {
        return view('manager.staff', [
            'staff' => User::with('staffProfile')->whereIn('role', ['staff', 'security'])->latest()->paginate(20),
        ]);
    }

    public function storeStaff(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(['staff', 'security'])],
            'staff_type' => ['required', 'string', 'max:100'],
            'employee_code' => ['required', 'string', 'max:100', 'unique:staff_profiles,employee_code'],
            'password' => ['nullable', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password'] ?? 'password'),
            'role' => $validated['role'],
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        StaffProfile::create([
            'user_id' => $user->id,
            'staff_type' => $validated['staff_type'],
            'employee_code' => $validated['employee_code'],
            'status' => 'active',
        ]);

        app(NotificationService::class)->toUser(
            $user->id,
            'staff_account_created',
            'Staff account created',
            'Your Nestora staff account is ready.',
            route($user->role === 'security' ? 'security.dashboard' : 'maintenance.dashboard', absolute: false)
        );

        return redirect()->route('manager.staff')->with('status', 'Staff member created.');
    }

    public function destroyStaff(User $staff): RedirectResponse
    {
        abort_unless(in_array($staff->role, ['staff', 'security'], true), 404);

        $staff->delete();

        return redirect()->route('manager.staff')->with('status', 'Staff member removed.');
    }

    public function bookings(): View
    {
        return view('manager.bookings', [
            'bookings' => FacilityBooking::with(['resident', 'facility'])->latest('booking_date')->paginate(20),
        ]);
    }

    public function updateBooking(Request $request, FacilityBooking $booking): RedirectResponse
    {
        $this->authorize('updateStatus', $booking);

        $status = $request->input('status', 'approved');
        abort_unless(in_array($status, ['approved', 'rejected'], true), 422);

        $booking->update(['status' => $status]);

        if ($status === 'approved') {
            $this->generateFacilityBill($booking->fresh(['facility', 'resident.residentProfile']));
        }

        $this->notify($booking->resident_id, 'facility_booking_'.$status, 'Facility booking '.$status, 'Your facility booking has been '.$status.'.');

        return redirect()->route('manager.bookings.index')->with('status', 'Booking '.$status.'.');
    }

    private function generateFacilityBill(FacilityBooking $booking): void
    {
        $facility = $booking->facility;
        $amount = (float) ($facility?->booking_fee ?? 0);

        if ($amount <= 0) {
            return;
        }

        $type = $facility?->name === 'Gym' ? 'gym_monthly_subscription' : 'facility_booking_fee_'.$booking->id;

        $bill = Bill::firstOrCreate(
            [
                'resident_id' => $booking->resident_id,
                'type' => $type,
                'billing_month' => now()->startOfMonth()->toDateString(),
            ],
            [
                'flat_id' => $booking->resident?->residentProfile?->flat_id,
                'bill_number' => 'FAC-'.strtoupper(Str::random(8)),
                'amount' => $amount,
                'due_date' => now()->addDays(7)->toDateString(),
                'status' => 'unpaid',
            ]
        );

        PaymentGatewayController::sessionForBill($bill);
        $this->notify($booking->resident_id, 'facility_bill_created', 'Facility bill generated', 'A bill has been generated for your approved '.$facility?->name.' request.');
    }

    public function emergencies(): View
    {
        return view('manager.emergencies', [
            'emergencies' => EmergencyRequest::with(['resident', 'flat'])->latest()->paginate(20),
        ]);
    }

    public function updateEmergency(Request $request, EmergencyRequest $emergency): RedirectResponse
    {
        $status = $request->input('status', 'resolved');
        abort_unless(in_array($status, ['open', 'in_progress', 'resolved'], true), 422);

        $emergency->update([
            'status' => $status,
            'resolved_at' => $status === 'resolved' ? now() : null,
        ]);

        return redirect()->route('manager.emergencies.index')->with('status', 'Emergency status updated.');
    }

    public function notices(): View
    {
        return view('manager.notices', [
            'notices' => Notice::latest('published_at')->paginate(20),
        ]);
    }

    public function storeNotice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $notice = Notice::create([
            'created_by' => $request->user()->id,
            'title' => $validated['title'],
            'body' => $validated['content'],
            'audience' => $validated['category'] ?? 'all',
            'published_at' => now(),
        ]);

        app(NotificationService::class)->toAll(
            'notice',
            $notice->title,
            $notice->body,
            route('notifications.index', absolute: false)
        );

        return redirect()->route('manager.notices.index')->with('status', 'Notice published.');
    }

    public function destroyNotice(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('manager.notices.index')->with('status', 'Notice deleted.');
    }

    private function validateFlat(Request $request): array
    {
        $validated = $request->validate([
            'building_id' => ['required', 'exists:buildings,id'],
            'number' => ['required', 'string', 'max:100'],
            'block' => ['nullable', 'string', 'max:50'],
            'floor' => ['nullable', 'integer', 'min:0'],
            'size' => ['nullable', 'numeric', 'min:0'],
            'beds' => ['nullable', 'integer', 'min:0'],
            'occupancy' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'building_id' => $validated['building_id'],
            'flat_number' => $validated['number'],
            'block' => $validated['block'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'area_sqft' => $validated['size'] ?? null,
            'bedrooms' => $validated['beds'] ?? 0,
            'type' => $validated['occupancy'] ?? null,
            'status' => in_array($validated['occupancy'] ?? null, ['vacant'], true) ? 'vacant' : 'occupied',
        ];
    }

    private function notify(?int $userId, string $type, string $title, ?string $body = null): void
    {
        app(NotificationService::class)->toUser($userId, $type, $title, $body);
    }
}
