<?php

namespace App\Enums;

/**
 * Occupancy and availability states for flats.
 */
enum FlatStatus: string
{
    case Occupied = 'occupied';
    case Vacant = 'vacant';
    case Maintenance = 'maintenance';
}
