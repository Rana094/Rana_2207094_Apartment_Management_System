<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Apartment building/block that contains flats.
 */
class Building extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function flats(): HasMany
    {
        return $this->hasMany(Flat::class);
    }
}
