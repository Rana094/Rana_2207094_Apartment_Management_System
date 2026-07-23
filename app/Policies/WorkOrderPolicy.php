<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    /**
     * Work order is visible to manager, assigned staff, and the complaint owner.
     */
    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === 'manager'
            || $workOrder->assigned_to === $user->id
            || $workOrder->complaint?->resident_id === $user->id;
    }

    /**
     * Only assigned maintenance staff can update the work order.
     */
    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $workOrder->assigned_to === $user->id && $user->role === 'staff';
    }
}
