<?php

namespace App\Enums;

/**
 * Standard lifecycle states for resident maintenance complaints.
 */
enum ComplaintStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Rejected = 'rejected';
}
