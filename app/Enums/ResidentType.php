<?php

namespace App\Enums;

enum ResidentType: string
{
    case Owner = 'owner';
    case Tenant = 'tenant';
}
