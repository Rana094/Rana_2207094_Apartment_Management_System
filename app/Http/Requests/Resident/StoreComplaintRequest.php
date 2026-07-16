<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComplaintRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->filled('urgency') && ! $this->filled('priority')) {
            $this->merge(['priority' => $this->input('urgency')]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->role === 'resident';
    }

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
