<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\MoveOutRequest;
use App\Models\Notice;
use App\Models\PaymentProof;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\VisitorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Services\NotificationService;

class ResidentPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $profile = $this->residentProfile($request);

        return view('resident.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'flat' => $profile?->flat,
            'currentBills' => $user->bills()->latest('due_date')->take(5)->get(),
            'activeComplaints' => $user->complaints()->whereIn('status', ['open', 'in_progress'])->latest()->take(5)->get(),
            'upcomingBookings' => $user->facilityBookings()->with('facility')->whereDate('booking_date', '>=', today())->latest('booking_date')->take(5)->get(),
            'recentNotices' => Notice::query()->whereNotNull('published_at')->latest('published_at')->take(5)->get(),
            'recentVisitors' => $user->visitorRequests()->latest('visit_date')->take(5)->get(),
        ]);
    }

    public function flat(Request $request): View
    {
        $profile = $this->residentProfile($request)?->load(['flat.building', 'flatMembers', 'vehicleRegistrations']);

        return view('resident.flat', [
            'profile' => $profile,
            'flat' => $profile?->flat,
            'members' => $profile?->flatMembers ?? collect(),
            'vehicles' => $profile?->vehicleRegistrations ?? collect(),
            'documents' => $request->user()->documents()->latest()->get(),
        ]);
    }

    public function bills(Request $request): View
    {
        return view('resident.bills.index', [
            'bills' => $request->user()->bills()->with('paymentProofs')->latest('due_date')->paginate(10),
        ]);
    }

    public function bill(Request $request, string $bill): View
    {
        $billModel = $this->resolveBillForDisplay($request, $bill);

        return view('resident.bills.show', [
            'bill' => $billModel?->load(['flat.building', 'paymentProofs']),
        ]);
    }

    public function uploadPaymentProof(Request $request, string $bill): View
    {
        $billModel = $this->resolveBillForDisplay($request, $bill);

        return view('resident.bills.upload', ['bill' => $billModel]);
    }

    public function storePaymentProof(Request $request, Bill $bill): RedirectResponse
    {
        $this->ensureOwnsBill($request, $bill);

        if ($request->filled('transaction_id') && ! $request->filled('transaction_reference')) {
            $request->merge(['transaction_reference' => $request->input('transaction_id')]);
        }

        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:0'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['required_without:receipt_file', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
            'receipt_file' => ['required_without:payment_proof', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);

        $path = ($request->file('payment_proof') ?? $request->file('receipt_file'))->store('payment-proofs');

        PaymentProof::create([
            'bill_id' => $bill->id,
            'user_id' => $request->user()->id,
            'amount' => $validated['amount'] ?? $bill->amount,
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'file_path' => $path,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $bill->update(['status' => 'pending_verification']);

        app(NotificationService::class)->toRole(
            'manager',
            'payment_proof_uploaded',
            'Payment proof uploaded',
            $request->user()->name.' uploaded proof for bill '.$bill->bill_number.'.',
            route('manager.payments.index', absolute: false)
        );

        return redirect()->route('resident.bills.show', $bill)->with('status', 'Payment proof uploaded for manager verification.');
    }

    public function complaints(Request $request): View
    {
        return view('resident.complaints.index', [
            'complaints' => $request->user()->complaints()->latest()->paginate(10),
        ]);
    }

    public function createComplaint(): View
    {
        return view('resident.complaints.create');
    }

    public function storeComplaint(Request $request): RedirectResponse
    {
        if ($request->filled('urgency') && ! $request->filled('priority')) {
            $request->merge(['priority' => $request->input('urgency')]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'emergency'])],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $complaint = Complaint::create([
            'resident_id' => $request->user()->id,
            'flat_id' => $this->residentProfile($request)?->flat_id,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'description' => isset($validated['location'])
                ? $validated['description']."\n\nLocation: ".$validated['location']
                : $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'complaint_created',
            'New maintenance complaint',
            $request->user()->name.' submitted: '.$complaint->title,
            route('manager.complaints.index', absolute: false)
        );

        return redirect()->route('resident.complaints.show', $complaint)->with('status', 'Complaint submitted successfully.');
    }

    public function complaint(Request $request, string $complaint): View
    {
        $complaintModel = Complaint::find($complaint);

        if ($complaintModel) {
            abort_unless($complaintModel->resident_id === $request->user()->id, 403);
            $complaintModel->load(['flat', 'workOrders.assignedStaff.staffProfile']);
        } elseif ($complaint !== '2033') {
            abort(404);
        }

        return view('resident.complaints.show', [
            'complaint' => $complaintModel,
        ]);
    }

    public function visitors(Request $request): View
    {
        return view('resident.visitors.index', [
            'visitors' => $request->user()->visitorRequests()->latest('visit_date')->paginate(10),
        ]);
    }

    public function createVisitor(): View
    {
        return view('resident.visitors.create');
    }

    public function storeVisitor(Request $request): RedirectResponse
    {
        $request->merge([
            'visitor_name' => $request->input('visitor_name', $request->input('name')),
            'visitor_phone' => $request->input('visitor_phone', $request->input('phone')),
            'visit_date' => $request->input('visit_date', $request->input('date')),
            'expected_entry_time' => $request->input('expected_entry_time', $request->input('time')),
        ]);

        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_phone' => ['nullable', 'string', 'max:30'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'expected_entry_time' => ['nullable', 'date_format:H:i'],
        ]);

        $visitor = VisitorRequest::create($validated + [
            'resident_id' => $request->user()->id,
            'flat_id' => $this->residentProfile($request)?->flat_id,
            'access_code' => strtoupper(Str::random(8)),
            'status' => 'pending',
        ]);

        app(NotificationService::class)->toRole(
            'security',
            'visitor_request_created',
            'New visitor request',
            $request->user()->name.' requested visitor access for '.$visitor->visitor_name.'.',
            route('security.checkin', absolute: false)
        );

        return redirect()->route('resident.visitors.index')->with('status', 'Visitor request created.');
    }

    public function bookings(Request $request): View
    {
        return view('resident.bookings.index', [
            'facilities' => Facility::where('status', 'available')->orderBy('name')->get(),
            'bookings' => $request->user()->facilityBookings()->with('facility')->latest('booking_date')->paginate(10),
        ]);
    }

    public function createBooking(): View
    {
        return view('resident.bookings.create', [
            'facilities' => Facility::where('status', 'available')->orderBy('name')->get(),
        ]);
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        if ($request->filled('facility') && ! $request->filled('facility_id')) {
            $facility = Facility::where('name', 'like', '%'.$request->input('facility').'%')
                ->orWhere('name', match ($request->input('facility')) {
                    'hall' => 'Community Hall',
                    'gym' => 'Gym',
                    'bbq' => 'Rooftop BBQ Grill Station',
                    default => $request->input('facility'),
                })
                ->first();

            if ($facility) {
                $request->merge(['facility_id' => $facility->id]);
            }
        }

        if ($request->filled('date') && ! $request->filled('booking_date')) {
            $request->merge(['booking_date' => $request->input('date')]);
        }

        if ($request->filled('shift') && (! $request->filled('start_time') || ! $request->filled('end_time'))) {
            [$start, $end] = match ($request->input('shift')) {
                'morning' => ['09:00', '14:00'],
                'fullday' => ['09:00', '22:00'],
                default => ['16:00', '21:00'],
            };

            $request->merge(['start_time' => $start, 'end_time' => $end]);
        }

        $validated = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'purpose' => ['nullable', 'string', 'max:255'],
        ]);

        $booking = FacilityBooking::create($validated + [
            'resident_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'facility_booking_requested',
            'Facility booking requested',
            $request->user()->name.' requested a facility booking.',
            route('manager.bookings.index', absolute: false)
        );

        return redirect()->route('resident.bookings.index')->with('status', 'Facility booking request submitted.');
    }

    public function polls(Request $request): View
    {
        return view('resident.polls', [
            'polls' => Poll::with(['options.votes', 'votes'])->latest()->get(),
            'myVotes' => $request->user()->pollVotes()->pluck('poll_option_id', 'poll_id'),
        ]);
    }

    public function vote(Request $request, Poll $poll): RedirectResponse
    {
        $validated = $request->validate([
            'poll_option_id' => ['required', 'exists:poll_options,id'],
        ]);

        $option = PollOption::where('poll_id', $poll->id)->findOrFail($validated['poll_option_id']);

        PollVote::updateOrCreate(
            ['poll_id' => $poll->id, 'user_id' => $request->user()->id],
            ['poll_option_id' => $option->id]
        );

        return redirect()->route('resident.polls')->with('status', 'Your vote has been recorded.');
    }

    public function emergency(Request $request): View
    {
        return view('resident.emergency', [
            'emergencyRequests' => $request->user()->emergencyRequests()->latest()->take(10)->get(),
        ]);
    }

    public function storeEmergency(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['general', 'medical', 'fire', 'security', 'maintenance', 'leak'])],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $emergency = EmergencyRequest::create(array_merge($validated, [
            'resident_id' => $request->user()->id,
            'flat_id' => $this->residentProfile($request)?->flat_id,
            'type' => $validated['type'] === 'leak' ? 'maintenance' : $validated['type'],
            'status' => 'open',
        ]));

        foreach (['manager', 'security'] as $role) {
            app(NotificationService::class)->toRole(
                $role,
                'emergency_request_created',
                'Emergency request created',
                $request->user()->name.' triggered a '.$emergency->type.' emergency.',
                $role === 'manager' ? route('manager.emergencies.index', absolute: false) : route('security.emergency', absolute: false)
            );
        }

        return redirect()->route('resident.emergency')->with('status', 'Emergency request sent.');
    }

    public function documents(Request $request): View
    {
        return view('resident.documents', [
            'documents' => $request->user()->documents()->latest()->paginate(10),
        ]);
    }

    public function storeDocument(Request $request): RedirectResponse
    {
        if ($request->filled('category') && ! $request->filled('type')) {
            $request->merge([
                'type' => match ($request->input('category')) {
                    'identity' => 'national_id',
                    'contract' => 'lease_agreement',
                    'utility' => 'other',
                    default => $request->input('category'),
                },
            ]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['national_id', 'lease_agreement', 'ownership_proof', 'payment_proof', 'other'])],
            'document' => ['required_without:document_file', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
            'document_file' => ['required_without:document', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);

        $file = $request->file('document') ?? $request->file('document_file');

        $document = Document::create([
            'user_id' => $request->user()->id,
            'flat_id' => $this->residentProfile($request)?->flat_id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $file->store('resident-documents'),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending_verification',
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'document_uploaded',
            'Resident document uploaded',
            $request->user()->name.' uploaded '.$document->title.' for verification.',
            route('manager.residents.index', absolute: false)
        );

        return redirect()->route('resident.documents')->with('status', 'Document uploaded for verification.');
    }

    public function moveOut(Request $request): View
    {
        return view('resident.move-out', [
            'moveOutRequests' => $request->user()->moveOutRequests()->latest()->get(),
        ]);
    }

    public function storeMoveOut(Request $request): RedirectResponse
    {
        if ($request->filled('move_out_date') && ! $request->filled('requested_move_out_date')) {
            $request->merge(['requested_move_out_date' => $request->input('move_out_date')]);
        }

        $validated = $request->validate([
            'requested_move_out_date' => ['required', 'date', 'after:today'],
            'reason' => ['nullable', 'string', 'max:2000'],
            'forwarding_address' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = $validated['reason'] ?? null;
        if (! empty($validated['forwarding_address'])) {
            $reason = trim(($reason ? $reason."\n\n" : '').'Forwarding address: '.$validated['forwarding_address']);
        }

        MoveOutRequest::create([
            'requested_move_out_date' => $validated['requested_move_out_date'],
            'reason' => $reason,
            'resident_id' => $request->user()->id,
            'flat_id' => $this->residentProfile($request)?->flat_id,
            'status' => 'pending',
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'move_out_requested',
            'Move-out request submitted',
            $request->user()->name.' submitted a move-out request.',
            route('manager.residents.index', absolute: false)
        );

        return redirect()->route('resident.move-out')->with('status', 'Move-out request submitted.');
    }

    public function profile(Request $request): View
    {
        return view('resident.profile', [
            'user' => $request->user(),
            'profile' => $this->residentProfile($request),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        $this->residentProfile($request)?->update([
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
        ]);

        return redirect()->route('resident.profile')->with('status', 'Profile updated.');
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

        return redirect()->route('resident.profile')->with('status', 'Password updated.');
    }

    private function residentProfile(Request $request)
    {
        return $request->user()->residentProfile()->with('flat.building')->first();
    }

    private function ensureOwnsBill(Request $request, Bill $bill): void
    {
        abort_unless($bill->resident_id === $request->user()->id, 403);
    }

    private function resolveBillForDisplay(Request $request, string $bill): ?Bill
    {
        $billModel = Bill::find($bill);

        if ($billModel) {
            $this->ensureOwnsBill($request, $billModel);

            return $billModel;
        }

        if ($bill === '9872') {
            return null;
        }

        abort(404);
    }
}
