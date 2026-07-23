<?php

namespace App\Enums;

/**
 * Account approval states controlled by manager review.
 */
enum UserStatus: string
{
    case PendingApproval = 'pending_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
