<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function residentProfiles(): HasMany
    {
        return $this->hasMany(ResidentProfile::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
