<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function view(User $user, Complaint $complaint): bool
    {
        return $user->role === 'manager'
            || $complaint->resident_id === $user->id
            || $complaint->workOrders()->where('assigned_to', $user->id)->exists();
    }

    public function assign(User $user, Complaint $complaint): bool
    {
        return $user->role === 'manager';
    }
}
