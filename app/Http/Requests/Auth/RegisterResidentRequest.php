<?php

namespace App\Http\Requests\Auth;

use App\Models\Flat;
use App\Services\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegisterResidentRequest extends FormRequest
{
    /**
     * Signup is public, so guests are allowed to submit this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate resident signup fields before AuthController creates the user.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'resident_type' => ['required', Rule::in(['owner', 'tenant'])],
            'flat_id' => ['required', 'integer', 'exists:flats,id'],
            'nid_document' => ['nullable', 'file', 'mimes:'.FileUploadService::DOCUMENT_MIMES, 'max:'.FileUploadService::MAX_DOCUMENT_KB],
        ];
    }

    /**
     * Run extra validation after normal rules to ensure the selected flat is truly available.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $flatId = $this->integer('flat_id');

                if (! $flatId) {
                    return;
                }

                // Scope checks vacancy plus active/pending resident conflicts.
                $isAvailable = Flat::query()
                    ->availableForSignup()
                    ->whereKey($flatId)
                    ->exists();

                if (! $isAvailable) {
                    $validator->errors()->add('flat_id', 'Please select a currently available flat.');
                }
            },
        ];
    }

    /**
     * User-friendly validation messages shown on the signup form.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'Password and confirm password do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'resident_type.required' => 'Please select whether you are an owner or tenant.',
            'resident_type.in' => 'Please select a valid resident type.',
            'flat_id.required' => 'Please select an available flat.',
            'flat_id.exists' => 'Please select a valid flat.',
            'nid_document.mimes' => 'The document must be a PDF, Word document, JPG, JPEG, or PNG file.',
            'nid_document.max' => 'The document must not be larger than 5MB.',
        ];
    }
}
