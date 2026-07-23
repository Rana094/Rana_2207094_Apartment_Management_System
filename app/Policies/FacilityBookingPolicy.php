<?php

namespace App\Policies;

use App\Models\FacilityBooking;
use App\Models\User;

class FacilityBookingPolicy
{
    /**
     * Only managers can approve or reject facility bookings.
     */
    public function updateStatus(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'manager';
    }

    /**
     * Managers can view all bookings; residents can view their own bookings.
     */
    public function view(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'manager' || $booking->resident_id === $user->id;
    }
}
