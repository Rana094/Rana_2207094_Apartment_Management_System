<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    public const DOCUMENT_MIMES = 'pdf,png,jpg,jpeg';
    public const IMAGE_OR_PDF_MIMES = 'pdf,png,jpg,jpeg';
    public const MAX_DOCUMENT_KB = 5120;
    public const MAX_PROOF_KB = 5120;

    public function store(UploadedFile $file, string $directory): string
    {
        $safeDirectory = trim($directory, '/\\');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $name = now()->format('YmdHis').'-'.Str::random(16).'.'.$extension;

        return $file->storeAs($safeDirectory, $name, 'private_uploads');
    }
}
