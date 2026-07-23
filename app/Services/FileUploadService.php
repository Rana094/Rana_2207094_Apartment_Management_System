<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Shared validation constants used by form requests/controllers for upload limits.
     */
    public const DOCUMENT_MIMES = 'pdf,png,jpg,jpeg,doc,docx';
    public const IMAGE_OR_PDF_MIMES = 'pdf,png,jpg,jpeg';
    public const MAX_DOCUMENT_KB = 5120;
    public const MAX_PROOF_KB = 5120;

    /**
     * Store uploads on the private disk with a generated safe filename.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        // Directory comes from application code, but trim it to avoid accidental nested separators.
        $safeDirectory = trim($directory, '/\\');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        // Random suffix prevents filename collisions and hides original user filenames.
        $name = now()->format('YmdHis').'-'.Str::random(16).'.'.$extension;

        return $file->storeAs($safeDirectory, $name, 'private_uploads');
    }
}
