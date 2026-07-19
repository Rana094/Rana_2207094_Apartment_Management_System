<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $resident_id
 * @property int|null $flat_id
 * @property string $title
 * @property string|null $category
 * @property string $description
 * @property string $priority
 * @property string $status
 *
 * Maintenance complaint submitted by a resident and assigned to staff through work orders.
 */
class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ComplaintMessage::class);
    }
}
