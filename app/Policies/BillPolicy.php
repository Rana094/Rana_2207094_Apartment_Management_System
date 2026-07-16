<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;

class BillPolicy
{
    public function view(User $user, Bill $bill): bool
    {
        return $user->role === 'manager' || $bill->resident_id === $user->id;
    }

    public function uploadPaymentProof(User $user, Bill $bill): bool
    {
        return $bill->resident_id === $user->id && $user->role === 'resident';
    }
}
