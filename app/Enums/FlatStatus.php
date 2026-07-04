<?php

namespace App\Enums;

enum FlatStatus: string
{
    case Occupied = 'occupied';
    case Vacant = 'vacant';
    case Maintenance = 'maintenance';
}
