<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\StoreSecurityIncidentRequest;
use App\Models\EmergencyRequest;
use App\Models\Flat;
use App\Models\ResidentProfile;
use App\Models\SecurityIncident;
use App\Models\User;
use App\Models\VisitorLog;
use App\Models\VisitorRequest;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SecurityPortalController extends Controller
{
    /**
     * Show security dashboard counts for today's visitor and emergency activity.
     */
    public function dashboard(Request $request): View
    {
        return view('security.dashboard', [
            'stats' => [
                'approved_visitors' => VisitorRequest::where('status', 'approved')->whereDate('visit_date', today())->count(),
                'pending_checkins' => VisitorRequest::where('status', 'approved')->whereNull('checked_in_at')->count(),
                'inside_visitors' => VisitorRequest::whereNotNull('checked_in_at')->whereNull('checked_out_at')->count(),
                'recent_checkouts' => VisitorRequest::whereNotNull('checked_out_at')->whereDate('checked_out_at', today())->count(),
            ],
            'recentVisitors' => VisitorRequest::with('flat')->latest('visit_date')->take(8)->get(),
            'emergencies' => EmergencyRequest::with(['resident', 'flat'])->whereIn('status', ['open', 'in_progress'])->latest()->take(5)->get(),
        ]);
    }

    /**
     * Show visitor check-in page and optionally load a passcode search result.
     */
    public function checkin(Request $request): View
    {
        $passcode = $request->string('passcode')->upper()->toString();

        return view('security.checkin', [
            'visitor' => $passcode ? $this->findVisitorByCode($passcode) : null,
            'flats' => Flat::with('building')->orderBy('flat_number')->get(),
            'recentCheckins' => VisitorLog::where('event_type', 'check_in')->latest('occurred_at')->take(10)->get(),
        ]);
    }

    /**
     * Check in a pre-approved visitor by passcode or create a manual walk-in entry.
     */
    public function storeCheckin(Request $request): RedirectResponse
    {
        if ($request->filled('passcode')) {
            $visitor = $this->findVisitorByCode($request->input('passcode'));
            abort_unless($visitor !== null, 404);

            $visitor->update([
                'status' => 'checked_in',
                'checked_in_at' => now(),
            ]);

            // Every check-in creates an immutable visitor log row for audit/history.
            $this->logVisitor($request, $visitor, 'check_in');

            return redirect()->route('security.checkin', ['passcode' => $visitor->access_code])
                ->with('status', 'Visitor checked in.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'category' => ['nullable', 'string', 'max:100'],
            'vehicle_plate' => ['nullable', 'string', 'max:100'],
            'flat_id' => ['required', 'exists:flats,id'],
            'purpose' => ['required', 'string', 'max:255'],
        ]);

        // Manual walk-ins are linked to the active resident of the selected flat.
        $resident = ResidentProfile::where('flat_id', $validated['flat_id'])->where('status', 'active')->first()?->user
            ?? User::where('role', 'resident')->where('status', 'approved')->first();

        abort_unless($resident !== null, 422, 'No approved resident exists for this manual visitor entry.');

        $visitor = VisitorRequest::create([
            'resident_id' => $resident->id,
            'flat_id' => $validated['flat_id'],
            'visitor_name' => $validated['name'],
            'visitor_phone' => $validated['phone'] ?? null,
            'purpose' => trim(($validated['category'] ?? 'walk_in').': '.$validated['purpose']),
            'visit_date' => today(),
            'expected_entry_time' => now()->format('H:i'),
            // WALK prefix marks this as a security-created visitor rather than resident-created pass.
            'access_code' => strtoupper('WALK'.Str::random(6)),
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        $this->logVisitor($request, $visitor, 'check_in', $validated['vehicle_plate'] ?? null);

        return redirect()->route('security.checkin', ['passcode' => $visitor->access_code])
            ->with('status', 'Manual visitor checked in.');
    }

    /**
     * Show checkout page and list visitors currently inside.
     */
    public function checkout(Request $request): View
    {
        $passcode = $request->string('passcode')->upper()->toString();

        return view('security.checkout', [
            'visitor' => $passcode ? $this->findVisitorByCode($passcode) : null,
            'insideVisitors' => VisitorRequest::whereNotNull('checked_in_at')->whereNull('checked_out_at')->latest('checked_in_at')->get(),
        ]);
    }

    /**
     * Check out a visitor and write a visitor log entry.
     */
    public function storeCheckout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'passcode' => ['required', 'string', 'max:50'],
        ]);

        $visitor = $this->findVisitorByCode($validated['passcode']);
        abort_unless($visitor !== null, 404);

        $visitor->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        // Logs page uses this record to show checkout history.
        $this->logVisitor($request, $visitor, 'check_out');

        return redirect()->route('security.checkout', ['passcode' => $visitor->access_code])
            ->with('status', 'Visitor checked out.');
    }

    /**
     * Show visitor log history from real check-in/check-out records.
     */
    public function logs(): View
    {
        return view('security.logs', [
            'logs' => VisitorLog::with(['flat.building', 'securityUser', 'visitorRequest.resident'])
                ->latest('occurred_at')
                ->paginate(30),
        ]);
    }

    /**
     * Show emergency alerts for security response.
     */
    public function emergency(): View
    {
        return view('security.emergency', [
            'emergencies' => EmergencyRequest::with(['resident', 'flat'])
                ->orderByRaw("case when status in ('open', 'in_progress') then 0 else 1 end")
                ->latest()
                ->paginate(20),
        ]);
    }

    /**
     * Mark an emergency resolved from the security portal.
     */
    public function updateEmergency(EmergencyRequest $emergency): RedirectResponse
    {
        $emergency->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'security_emergency_resolved',
            'Emergency alert resolved by security',
            '#AL-'.$emergency->id.' was marked resolved by gate security.',
            route('manager.emergencies.index', absolute: false)
        );

        return redirect()->route('security.emergency')->with('status', 'Emergency alert resolved.');
    }

    /**
     * Create an emergency alert directly from the gate/security terminal.
     */
    public function triggerEmergency(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Security-created alerts still need a resident/flat link for existing emergency schema.
        $resident = User::where('role', 'resident')->where('status', 'approved')->first();
        abort_unless($resident !== null, 422, 'At least one approved resident is required before creating a security emergency alert.');

        $profile = $resident->residentProfile;

        EmergencyRequest::create([
            'resident_id' => $resident->id,
            'flat_id' => $profile?->flat_id,
            'type' => $validated['type'],
            'message' => $validated['message'] ?? 'Security guard triggered an emergency alert from the gate terminal.',
            'status' => 'open',
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'security_emergency',
            'Security emergency triggered',
            $validated['message'] ?? null,
            route('manager.emergencies.index', absolute: false)
        );

        return redirect()->route('security.emergency')->with('status', 'Emergency alert dispatched.');
    }

    /**
     * List security incident reports and flats for new reports.
     */
    public function incidents(): View
    {
        return view('security.incidents', [
            'incidents' => SecurityIncident::with(['flat', 'reporter'])->latest('occurred_at')->paginate(20),
            'flats' => Flat::orderBy('flat_number')->get(),
        ]);
    }

    /**
     * Store a security incident and notify managers.
     */
    public function storeIncident(StoreSecurityIncidentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Incident reports are separate from emergency alerts because they may be non-urgent.
        SecurityIncident::create([
            'reported_by' => $request->user()->id,
            'flat_id' => $validated['flat_id'] ?? null,
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'open',
            'occurred_at' => $validated['date'].' '.$validated['time'],
        ]);

        app(NotificationService::class)->toRole(
            'manager',
            'security_incident',
            $validated['subject'],
            $validated['description'],
            route('manager.emergencies.index', absolute: false)
        );

        return redirect()->route('security.incidents')->with('status', 'Security incident report filed.');
    }

    /**
     * Find a visitor request by passcode/access code.
     */
    private function findVisitorByCode(string $passcode): ?VisitorRequest
    {
        return VisitorRequest::with(['flat', 'resident'])
            ->where('access_code', strtoupper(trim($passcode)))
            ->first();
    }

    /**
     * Record check-in/check-out activity for manager/security audit history.
     */
    private function logVisitor(Request $request, VisitorRequest $visitor, string $eventType, ?string $vehiclePlate = null): void
    {
        VisitorLog::create([
            'visitor_request_id' => $visitor->id,
            'flat_id' => $visitor->flat_id,
            'security_user_id' => $request->user()->id,
            'visitor_name' => $visitor->visitor_name,
            'visitor_phone' => $visitor->visitor_phone,
            'access_code' => $visitor->access_code,
            'event_type' => $eventType,
            'purpose' => $visitor->purpose,
            'vehicle_plate' => $vehiclePlate,
            'occurred_at' => now(),
        ]);
    }
}
