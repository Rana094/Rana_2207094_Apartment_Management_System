<?php

namespace App\Http\Requests\Resident;

use App\Services\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentProofRequest extends FormRequest
{
    /**
     * Normalize older transaction_id field into transaction_reference.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('transaction_id') && ! $this->filled('transaction_reference')) {
            $this->merge(['transaction_reference' => $this->input('transaction_id')]);
        }
    }

    /**
     * Only residents can upload payment proofs.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'resident';
    }

    /**
     * Validate proof file and optional transaction details.
     */
    public function rules(): array
    {
        return [
            'amount' => ['nullable', 'numeric', 'min:0'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['required_without:receipt_file', 'file', 'mimes:'.FileUploadService::IMAGE_OR_PDF_MIMES, 'max:'.FileUploadService::MAX_PROOF_KB],
            'receipt_file' => ['required_without:payment_proof', 'file', 'mimes:'.FileUploadService::IMAGE_OR_PDF_MIMES, 'max:'.FileUploadService::MAX_PROOF_KB],
        ];
    }
}
