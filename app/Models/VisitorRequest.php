<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $resident_id
 * @property int|null $flat_id
 * @property string $visitor_name
 * @property string|null $visitor_phone
 * @property string|null $purpose
 * @property Carbon $visit_date
 * @property string|null $expected_entry_time
 * @property string $access_code
 * @property string $status
 * @property Carbon|null $checked_in_at
 * @property Carbon|null $checked_out_at
 */
/**
 * Visitor pass requested by a resident or manually created by security.
 */
class VisitorRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(VisitorLog::class);
    }
}
