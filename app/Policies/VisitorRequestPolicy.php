<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitorRequest;

class VisitorRequestPolicy
{
    public function view(User $user, VisitorRequest $visitorRequest): bool
    {
        return in_array($user->role, ['manager', 'security'], true)
            || $visitorRequest->resident_id === $user->id;
    }

    public function processGate(User $user, VisitorRequest $visitorRequest): bool
    {
        return $user->role === 'security';
    }
}
