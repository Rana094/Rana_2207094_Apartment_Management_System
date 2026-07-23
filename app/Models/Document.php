<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $flat_id
 * @property string $title
 * @property string $type
 * @property string $file_path
 * @property string|null $mime_type
 * @property int|null $file_size
 * @property string $status
 * @property Carbon|null $verified_at
 * @property int|null $verified_by
 */
class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Authenticated download URL for this private document.
     */
    public function secureUrl(): string
    {
        return route('files.documents.show', $this);
    }

    /**
     * Authenticated preview URL for browser-previewable files.
     */
    public function previewUrl(): string
    {
        return route('files.documents.show', ['document' => $this, 'preview' => 1]);
    }

    /**
     * Only images and PDFs are previewed inline; Word files download instead.
     */
    public function isPreviewable(): bool
    {
        $mimeType = strtolower((string) $this->mime_type);
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));

        return str_starts_with($mimeType, 'image/')
            || $mimeType === 'application/pdf'
            || in_array($extension, ['pdf', 'png', 'jpg', 'jpeg'], true);
    }
}
