<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;

class BillPolicy
{
    /**
     * Managers can view all bills; residents can only view their own bills.
     */
    public function view(User $user, Bill $bill): bool
    {
        return $user->role === 'manager' || $bill->resident_id === $user->id;
    }

    /**
     * Only the owning resident can upload proof for their bill.
     */
    public function uploadPaymentProof(User $user, Bill $bill): bool
    {
        return $bill->resident_id === $user->id && $user->role === 'resident';
    }
}
