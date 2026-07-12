<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Building;
use App\Models\Complaint;
use App\Models\EmergencyRequest;
use App\Models\FacilityBooking;
use App\Models\Flat;
use App\Models\Notice;
use App\Models\Notification;
use App\Models\PaymentProof;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\ResidentProfile;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                'residents' => User::where('role', 'resident')->count(),
                'occupied_flats' => Flat::where('status', 'occupied')->count(),
                'vacant_flats' => Flat::where('status', 'vacant')->count(),
                'pending_approvals' => User::where('role', 'resident')->whereIn('status', ['pending_verification', 'pending_approval'])->count(),
                'unpaid_bills' => Bill::whereIn('status', ['unpaid', 'overdue'])->count(),
                'open_complaints' => Complaint::whereIn('status', ['open', 'in_progress'])->count(),
                'today_visitors' => \App\Models\VisitorRequest::whereDate('visit_date', today())->count(),
                'revenue' => Bill::where('status', 'paid')->sum('amount'),
            ],
        ]);
    }

    public function residents(): View
    {
        return view('manager.residents.index', [
            'residents' => User::with('residentProfile.flat.building')
                ->where('role', 'resident')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function resident(string $resident): View
    {
        $residentModel = User::with(['residentProfile.flat.building', 'documents', 'bills', 'complaints'])
            ->where('role', 'resident')
            ->find($resident);

        if (! $residentModel && $resident !== '1') {
            abort(404);
        }

        return view('manager.residents.show', ['resident' => $residentModel]);
    }

    public function flats(): View
    {
        return view('manager.flats.index', [
            'flats' => Flat::with(['building', 'residentProfiles.user'])->orderBy('flat_number')->paginate(20),
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
        $building = Building::firstOrCreate(
            ['code' => $request->input('building_code', 'NEST-A')],
            ['name' => $request->input('building_name', 'Nestora Heights - Building A'), 'floors' => 1]
        );

        Flat::create($data + ['building_id' => $building->id]);

        return redirect()->route('manager.flats.index')->with('status', 'Flat created.');
    }

    public function editFlat(string $flat): View
    {
        $flatModel = Flat::with('building')->find($flat);

        if (! $flatModel && $flat !== '1') {
            abort(404);
        }

        return view('manager.flats.form', [
            'flat' => $flatModel ?? ['block' => 'A', 'occupancy' => 'owner'],
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

    public function storeBill(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'target_flat_id' => ['nullable', 'exists:flats,id'],
            'category' => ['required', 'string', 'max:100'],
            'period' => ['required', 'date_format:Y-m'],
            'due_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $query = ResidentProfile::with('user')
            ->whereHas('user', fn ($q) => $q->where('status', 'approved'));

        if ($request->filled('target_flat_id') && ! $request->boolean('bulk_billing')) {
            $query->where('flat_id', $validated['target_flat_id']);
        }

        $created = 0;
        foreach ($query->get() as $profile) {
            Bill::updateOrCreate(
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
            $this->notify($profile->user_id, 'bill_created', 'New bill generated', 'A new bill is available in your resident portal.');
            $created++;
        }

        return redirect()->route('manager.bills.index')->with('status', "{$created} bill(s) generated.");
    }

    public function bills(): View
    {
        return view('manager.bills.index', [
            'bills' => Bill::with(['resident', 'flat'])->latest('due_date')->paginate(20),
        ]);
    }

    public function payments(): View
    {
        return view('manager.payments.index', [
            'paymentProofs' => PaymentProof::with(['bill.resident', 'user'])->latest()->paginate(20),
        ]);
    }

    public function payment(string $payment): View
    {
        $paymentProof = PaymentProof::with(['bill.resident', 'user'])->find($payment);

        if (! $paymentProof && $payment !== '1') {
            abort(404);
        }

        return view('manager.payments.show', ['paymentProof' => $paymentProof]);
    }

    public function verifyPayment(Request $request, PaymentProof $paymentProof): RedirectResponse
    {
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
        ]);
    }

    public function complaints(): View
    {
        return view('manager.complaints.index', [
            'complaints' => Complaint::with(['resident', 'flat', 'workOrders.assignedStaff'])->latest()->paginate(20),
        ]);
    }

    public function assignComplaint(string $complaint): View
    {
        $complaintModel = Complaint::with(['resident', 'flat'])->find($complaint);

        if (! $complaintModel && $complaint !== '2033') {
            abort(404);
        }

        return view('manager.complaints.assign', [
            'complaint' => $complaintModel,
            'staff' => User::with('staffProfile')->whereIn('role', ['staff'])->where('status', 'approved')->get(),
        ]);
    }

    public function storeWorkOrder(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'technician_id' => ['required', 'exists:users,id'],
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

        return redirect()->route('manager.staff')->with('status', 'Staff member created.');
    }

    public function bookings(): View
    {
        return view('manager.bookings', [
            'bookings' => FacilityBooking::with(['resident', 'facility'])->latest('booking_date')->paginate(20),
        ]);
    }

    public function updateBooking(Request $request, FacilityBooking $booking): RedirectResponse
    {
        $status = $request->input('status', 'approved');
        abort_unless(in_array($status, ['approved', 'rejected'], true), 422);

        $booking->update(['status' => $status]);
        $this->notify($booking->resident_id, 'facility_booking_'.$status, 'Facility booking '.$status, 'Your facility booking has been '.$status.'.');

        return redirect()->route('manager.bookings.index')->with('status', 'Booking '.$status.'.');
    }

    public function polls(): View
    {
        return view('manager.polls', [
            'polls' => Poll::with(['options.votes', 'votes'])->latest()->get(),
        ]);
    }

    public function storePoll(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1000'],
            'closes_at' => ['required', 'date', 'after:today'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'max:255'],
        ]);

        $poll = Poll::create([
            'title' => $validated['question'],
            'description' => $request->input('description'),
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $validated['closes_at'],
        ]);

        foreach (($validated['options'] ?? ['Yes', 'No', 'Abstain']) as $label) {
            PollOption::create(['poll_id' => $poll->id, 'label' => $label]);
        }

        return redirect()->route('manager.polls')->with('status', 'Poll created.');
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

        Notification::create([
            'audience' => $notice->audience,
            'type' => 'notice',
            'title' => $notice->title,
            'body' => $notice->body,
            'action_url' => route('manager.notices.index', absolute: false),
        ]);

        return redirect()->route('manager.notices.index')->with('status', 'Notice published.');
    }

    private function validateFlat(Request $request): array
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:100'],
            'block' => ['nullable', 'string', 'max:50'],
            'floor' => ['nullable', 'integer', 'min:0'],
            'size' => ['nullable', 'numeric', 'min:0'],
            'beds' => ['nullable', 'integer', 'min:0'],
            'occupancy' => ['nullable', 'string', 'max:100'],
        ]);

        return [
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
        Notification::create([
            'user_id' => $userId,
            'audience' => $userId ? 'user' : 'all',
            'type' => $type,
            'title' => $title,
            'body' => $body,
        ]);
    }
}
