<?php

namespace App\Policies;

use App\Models\FacilityBooking;
use App\Models\User;

class FacilityBookingPolicy
{
    public function updateStatus(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'manager';
    }

    public function view(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'manager' || $booking->resident_id === $user->id;
    }
}
