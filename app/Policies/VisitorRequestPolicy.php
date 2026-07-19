<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitorRequest;

class VisitorRequestPolicy
{
    /**
     * Visitor requests are visible to managers, security, and the requesting resident.
     */
    public function view(User $user, VisitorRequest $visitorRequest): bool
    {
        return in_array($user->role, ['manager', 'security'], true)
            || $visitorRequest->resident_id === $user->id;
    }

    /**
     * Only security staff can process gate check-in/check-out actions.
     */
    public function processGate(User $user, VisitorRequest $visitorRequest): bool
    {
        return $user->role === 'security';
    }
}
