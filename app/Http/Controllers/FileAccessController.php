<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PaymentProof;
use App\Models\User;
use App\Models\WorkOrderNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileAccessController extends Controller
{
    public function document(Request $request, Document $document): StreamedResponse
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'manager' || $document->user_id === $user->id,
            403
        );

        return $this->download($document->file_path, $document->title);
    }

    public function residentSignupDocument(Request $request, User $resident): StreamedResponse
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'manager' || $resident->id === $user->id,
            403
        );

        abort_unless($resident->document_path, 404);

        return $this->download($resident->document_path, $resident->name.' verification document');
    }

    public function paymentProof(Request $request, PaymentProof $paymentProof): StreamedResponse
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'manager' || $paymentProof->user_id === $user->id,
            403
        );

        return $this->download($paymentProof->file_path, 'payment-proof-'.$paymentProof->id);
    }

    public function workOrderProof(Request $request, WorkOrderNote $note): StreamedResponse
    {
        $user = $request->user();
        $workOrder = $note->workOrder()->with('complaint')->firstOrFail();

        abort_unless(
            $user->role === 'manager'
                || $workOrder->assigned_to === $user->id
                || $workOrder->complaint?->resident_id === $user->id,
            403
        );

        abort_unless($note->proof_path, 404);

        return $this->download($note->proof_path, 'work-order-proof-'.$note->id);
    }

    private function download(string $path, string $name): StreamedResponse
    {
        abort_unless(Storage::disk('private_uploads')->exists($path), 404);

        return Storage::disk('private_uploads')->download($path, $name);
    }
}
