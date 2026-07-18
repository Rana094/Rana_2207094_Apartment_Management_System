<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PaymentProof;
use App\Models\User;
use App\Models\WorkOrderNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileAccessController extends Controller
{
    public function document(Request $request, Document $document): BinaryFileResponse
    {
        $this->authorize('view', $document);

        return $this->serve(
            $document->file_path,
            $document->title,
            $document->mime_type,
            $request->boolean('preview') && $document->isPreviewable()
        );
    }

    public function residentSignupDocument(Request $request, User $resident): BinaryFileResponse
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'manager' || $resident->id === $user->id,
            403
        );

        $documentPath = $resident->document_path;
        abort_unless(is_string($documentPath) && $documentPath !== '', 404);

        return $this->serve($documentPath, $resident->name.' verification document', inline: $request->boolean('preview'));
    }

    public function paymentProof(Request $request, PaymentProof $paymentProof): BinaryFileResponse
    {
        $this->authorize('view', $paymentProof);

        return $this->serve($paymentProof->file_path, 'payment-proof-'.$paymentProof->id, $paymentProof->mime_type);
    }

    public function workOrderProof(Request $request, WorkOrderNote $note): BinaryFileResponse
    {
        $user = $request->user();
        $workOrder = $note->workOrder()->with('complaint')->firstOrFail();

        $this->authorize('view', $workOrder);

        $proofPath = $note->proof_path;
        abort_unless(is_string($proofPath) && $proofPath !== '', 404);

        return $this->serve($proofPath, 'work-order-proof-'.$note->id);
    }

    private function serve(string $path, string $name, ?string $mimeType = null, bool $inline = false): BinaryFileResponse
    {
        $disk = Storage::disk('private_uploads');

        abort_unless($disk->exists($path), 404);

        $absolutePath = $disk->path($path);
        $fileName = $this->downloadName($name, $path);
        $headers = array_filter([
            'Content-Type' => $mimeType ?: $disk->mimeType($path),
        ]);

        if ($inline) {
            $headers['Content-Disposition'] = 'inline; filename="'.$fileName.'"';

            return response()->file($absolutePath, $headers);
        }

        return response()->download($absolutePath, $fileName, $headers);
    }

    private function downloadName(string $name, string $path): string
    {
        $fileName = trim(str_replace(['/', '\\', '"'], '-', $name));
        $fileName = $fileName !== '' ? $fileName : 'document';
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension !== '' && Str::lower(pathinfo($fileName, PATHINFO_EXTENSION)) !== Str::lower($extension)) {
            $fileName .= '.'.$extension;
        }

        return $fileName;
    }
}
