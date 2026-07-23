<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Complaint can be viewed by manager, owning resident, or assigned maintenance staff.
     */
    public function view(User $user, Complaint $complaint): bool
    {
        return $user->role === 'manager'
            || $complaint->resident_id === $user->id
            || $complaint->workOrders()->where('assigned_to', $user->id)->exists();
    }

    /**
     * Only managers can assign complaints to maintenance staff.
     */
    public function assign(User $user, Complaint $complaint): bool
    {
        return $user->role === 'manager';
    }
}
