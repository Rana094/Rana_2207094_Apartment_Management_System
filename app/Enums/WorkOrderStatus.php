<?php

namespace App\Enums;

/**
 * Maintenance staff task states.
 */
enum WorkOrderStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Urgent = 'urgent';
}
