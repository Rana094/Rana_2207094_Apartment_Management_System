<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentFacilityBookingMiddleware
{
    private const ALLOWED_FACILITIES = [
        'Community Hall',
        'Rooftop BBQ Grill Station',
        'Gym',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();

        abort_unless($resident?->role === 'resident', 403);

        $facilityId = $request->input('facility_id');
        $facility = Facility::whereKey($facilityId)->first();

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

        if ($facility->name === 'Gym' && $this->hasActiveGymSubscription($resident->id, $facility->id)) {
            return redirect()
                ->route('resident.bookings.create')
                ->withErrors(['facility_id' => 'You already have a pending or approved gym subscription request.'])
                ->withInput();
        }

        $request->attributes->set('selectedFacility', $facility);

        return $next($request);
    }

    private function hasActiveGymSubscription(int $residentId, int $facilityId): bool
    {
        return Facility::find($facilityId)?->bookings()
            ->where('resident_id', $residentId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists() ?? false;
    }
}
