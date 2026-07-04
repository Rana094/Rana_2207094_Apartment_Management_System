<?php

namespace App\Enums;

enum WorkOrderStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Urgent = 'urgent';
}
