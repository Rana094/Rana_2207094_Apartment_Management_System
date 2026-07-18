<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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

    public function pendingResidentRequests(): HasMany
    {
        return $this->hasMany(User::class, 'requested_flat_id')
            ->where('role', 'resident')
            ->whereIn('status', ['pending_verification', 'pending_approval']);
    }

    public function scopeAvailableForSignup(Builder $query): Builder
    {
        return $query
            ->where('status', 'vacant')
            ->whereDoesntHave('residentProfiles', fn (Builder $profile) => $profile->where('status', 'active'))
            ->whereDoesntHave('pendingResidentRequests');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function visitorRequests(): HasMany
    {
        return $this->hasMany(VisitorRequest::class);
    }

    public function emergencyRequests(): HasMany
    {
        return $this->hasMany(EmergencyRequest::class);
    }

    public function moveOutRequests(): HasMany
    {
        return $this->hasMany(MoveOutRequest::class);
    }
}
