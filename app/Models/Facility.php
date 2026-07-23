<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Shared building facility such as hall, rooftop BBQ, or gym.
 */
class Facility extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'booking_fee' => 'decimal:2',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(FacilityBooking::class);
    }
}
