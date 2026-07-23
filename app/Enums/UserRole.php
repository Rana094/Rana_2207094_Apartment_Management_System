<?php

namespace App\Enums;

/**
 * Portal roles used for authentication and route access.
 */
enum UserRole: string
{
    case Resident = 'resident';
    case Manager = 'manager';
    case Security = 'security';
    case Staff = 'staff';
}
