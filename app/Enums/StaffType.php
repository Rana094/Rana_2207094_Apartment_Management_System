<?php

namespace App\Enums;

enum StaffType: string
{
    case Maintenance = 'maintenance';
    case Security = 'security';
    case Cleaner = 'cleaner';
    case Electrician = 'electrician';
    case Plumber = 'plumber';
}
