<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $approved_at
 * @property int|null $approved_by
 * @property string|null $rejection_reason
 * @property string $password
 * @property string $role
 * @property string $status
 * @property string|null $resident_type
 * @property string|null $flat_info
 * @property int|null $requested_flat_id
 * @property string|null $document_path
 */
class User extends Authenticatable
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
        'requested_flat_id',
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

    /**
     * A user becomes portal-ready only after manager approval.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->approved_at !== null;
    }

    /**
     * Resolve the correct dashboard route based on user role.
     */
    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'manager' => 'manager.dashboard',
            'security' => 'security.dashboard',
            'staff' => 'maintenance.dashboard',
            default => 'resident.dashboard',
        };
    }

    /**
     * Approved resident details such as assigned flat and emergency contact.
     */
    public function residentProfile(): HasOne
    {
        return $this->hasOne(ResidentProfile::class);
    }

    /**
     * Flat selected during signup before manager approval.
     */
    public function requestedFlat(): BelongsTo
    {
        return $this->belongsTo(Flat::class, 'requested_flat_id');
    }

    /**
     * Staff/security profile information for employee accounts.
     */
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

    /**
     * Secure URL for the signup document uploaded before approval.
     */
    public function signupDocumentUrl(): ?string
    {
        return $this->document_path ? route('files.resident-signup.show', $this) : null;
    }

    /**
     * Maintenance work orders assigned to this user when they are staff.
     */
    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    /**
     * Work orders created by this user when they are a manager.
     */
    public function createdWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_by');
    }
}
