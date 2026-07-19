<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentFacilityBookingMiddleware
{
    /**
     * Facilities residents are allowed to request from the resident portal.
     */
    private const ALLOWED_FACILITIES = [
        'Community Hall',
        'Rooftop BBQ Grill Station',
        'Gym',
    ];

    /**
     * Validate facility booking rules before the controller stores the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();

        abort_unless($resident?->role === 'resident', 403);

        // Load the selected facility once and reuse it later in the controller.
        $facilityId = $request->input('facility_id');
        $facility = Facility::whereKey($facilityId)->first();

        // Prevent booking facilities that are not part of the resident-facing workflow.
        if (! $facility || ! in_array($facility->name, self::ALLOWED_FACILITIES, true)) {
            return redirect()
                ->route('resident.bookings.create')
                ->withErrors(['facility_id' => 'Only Community Hall, Rooftop BBQ, and Gym subscription requests are available.'])
                ->withInput();
        }

        if ($facility->status !== 'available') {
            return redirect()
                ->route('resident.bookings.create')
                ->withErrors(['facility_id' => 'This facility is not available right now.'])
                ->withInput();
        }

        // A resident should not create duplicate active gym subscription requests.
        if ($facility->name === 'Gym' && $this->hasActiveGymSubscription($resident->id, $facility->id)) {
            return redirect()
                ->route('resident.bookings.create')
                ->withErrors(['facility_id' => 'You already have a pending or approved gym subscription request.'])
                ->withInput();
        }

        // Share the loaded facility with the controller to avoid another database lookup.
        $request->attributes->set('selectedFacility', $facility);

        return $next($request);
    }

    /**
     * Check whether this resident already has an active gym subscription workflow.
     */
    private function hasActiveGymSubscription(int $residentId, int $facilityId): bool
    {
        return Facility::find($facilityId)?->bookings()
            ->where('resident_id', $residentId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists() ?? false;
    }
}
