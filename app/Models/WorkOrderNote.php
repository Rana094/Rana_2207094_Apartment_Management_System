<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $work_order_id
 * @property int|null $user_id
 * @property string|null $status
 * @property string $remarks
 * @property string|null $proof_path
 * @property Carbon $noted_at
 *
 * Repair/status note submitted by staff for a maintenance work order.
 */
class WorkOrderNote extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'noted_at' => 'datetime',
        ];
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Secure route for viewing optional completion proof.
     */
    public function secureProofUrl(): ?string
    {
        return $this->proof_path ? route('files.work-order-proofs.show', $this) : null;
    }
}
