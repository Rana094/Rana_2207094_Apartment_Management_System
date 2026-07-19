<?php

namespace App\Http\Requests\Maintenance;

use App\Services\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
{
    /**
     * Only maintenance staff can submit work-order status updates.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'staff';
    }

    /**
     * Validate progress remarks and optional proof uploaded by the staff member.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string'],
            'remarks' => ['required', 'string', 'max:5000'],
            'completion_photo' => ['nullable', 'file', 'mimes:'.FileUploadService::IMAGE_OR_PDF_MIMES, 'max:'.FileUploadService::MAX_PROOF_KB],
            'completion_proof' => ['nullable', 'file', 'mimes:'.FileUploadService::IMAGE_OR_PDF_MIMES, 'max:'.FileUploadService::MAX_PROOF_KB],
        ];
    }
}
