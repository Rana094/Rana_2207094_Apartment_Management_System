<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'email_verified_at',
        'password',
        'role',
        'status',
        'resident_type',
        'flat_info',
        'document_path',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->approved_at !== null;
    }

    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'manager' => 'manager.dashboard',
            'security' => 'security.dashboard',
            'staff' => 'maintenance.dashboard',
            default => 'resident.dashboard',
        };
    }

    public function residentProfile(): HasOne
    {
        return $this->hasOne(ResidentProfile::class);
    }

    public function staffProfile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'resident_id');
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'resident_id');
    }

    public function visitorRequests(): HasMany
    {
        return $this->hasMany(VisitorRequest::class, 'resident_id');
    }

    public function facilityBookings(): HasMany
    {
        return $this->hasMany(FacilityBooking::class, 'resident_id');
    }

    public function pollVotes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function emergencyRequests(): HasMany
    {
        return $this->hasMany(EmergencyRequest::class, 'resident_id');
    }

    public function moveOutRequests(): HasMany
    {
        return $this->hasMany(MoveOutRequest::class, 'resident_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function visitorLogs(): HasMany
    {
        return $this->hasMany(VisitorLog::class, 'security_user_id');
    }

    public function reportedSecurityIncidents(): HasMany
    {
        return $this->hasMany(SecurityIncident::class, 'reported_by');
    }

    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function createdWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_by');
    }
}
