<?php

namespace App\Policies;

use App\Models\PaymentProof;
use App\Models\User;

class PaymentProofPolicy
{
    public function view(User $user, PaymentProof $paymentProof): bool
    {
        return $user->role === 'manager' || $paymentProof->user_id === $user->id;
    }

    public function verify(User $user, PaymentProof $paymentProof): bool
    {
        return $user->role === 'manager';
    }
}
