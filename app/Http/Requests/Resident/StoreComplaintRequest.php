<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComplaintRequest extends FormRequest
{
    /**
     * Normalize urgency from the UI into the priority field stored in the database.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('urgency') && ! $this->filled('priority')) {
            $this->merge(['priority' => $this->input('urgency')]);
        }
    }

    /**
     * Only residents can create maintenance complaints.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'resident';
    }

    /**
     * Validate complaint content and allowed urgency levels.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'emergency'])],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
}
