<?php

namespace App\Enums;

/**
 * Shared urgency levels for complaints and work orders.
 */
enum Priority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Emergency = 'emergency';
}
