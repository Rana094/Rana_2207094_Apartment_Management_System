<?php

namespace App\Policies;

use App\Models\PaymentProof;
use App\Models\User;

class PaymentProofPolicy
{
    /**
     * Payment proof is visible to managers and to the resident who uploaded it.
     */
    public function view(User $user, PaymentProof $paymentProof): bool
    {
        return $user->role === 'manager' || $paymentProof->user_id === $user->id;
    }

    /**
     * Only managers can verify or reject uploaded payment proof.
     */
    public function verify(User $user, PaymentProof $paymentProof): bool
    {
        return $user->role === 'manager';
    }
}
