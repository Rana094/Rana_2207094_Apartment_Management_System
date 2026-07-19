<?php

namespace App\Enums;

/**
 * Staff job categories used in staff profiles.
 */
enum StaffType: string
{
    case Maintenance = 'maintenance';
    case Security = 'security';
    case Cleaner = 'cleaner';
    case Electrician = 'electrician';
    case Plumber = 'plumber';
}
