<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === 'manager'
            || $workOrder->assigned_to === $user->id
            || $workOrder->complaint?->resident_id === $user->id;
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $workOrder->assigned_to === $user->id && $user->role === 'staff';
    }
}
