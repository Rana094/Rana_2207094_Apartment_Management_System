<?php

namespace App\Enums;

enum UserRole: string
{
    case Resident = 'resident';
    case Manager = 'manager';
    case Security = 'security';
    case Staff = 'staff';
}
