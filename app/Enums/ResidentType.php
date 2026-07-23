<?php

namespace App\Enums;

/**
 * Resident ownership types used during signup and approval.
 */
enum ResidentType: string
{
    case Owner = 'owner';
    case Tenant = 'tenant';
}
