<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlatMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function residentProfile(): BelongsTo
    {
        return $this->belongsTo(ResidentProfile::class);
    }
}
