<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PaymentProof;
use App\Models\User;
use App\Models\WorkOrderNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileAccessController extends Controller
{
    public function document(Request $request, Document $document): BinaryFileResponse
    {
        $this->authorize('view', $document);

        return $this->download($document->file_path, $document->title);
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

        return $this->download($documentPath, $resident->name.' verification document');
    }

    public function paymentProof(Request $request, PaymentProof $paymentProof): BinaryFileResponse
    {
        $this->authorize('view', $paymentProof);

        return $this->download($paymentProof->file_path, 'payment-proof-'.$paymentProof->id);
    }

    public function workOrderProof(Request $request, WorkOrderNote $note): BinaryFileResponse
    {
        $user = $request->user();
        $workOrder = $note->workOrder()->with('complaint')->firstOrFail();

        $this->authorize('view', $workOrder);

        $proofPath = $note->proof_path;
        abort_unless(is_string($proofPath) && $proofPath !== '', 404);

        return $this->download($proofPath, 'work-order-proof-'.$note->id);
    }

    private function download(string $path, string $name): BinaryFileResponse
    {
        $disk = Storage::disk('private_uploads');

        abort_unless($disk->exists($path), 404);

        return response()->download($disk->path($path), $name);
    }
}
