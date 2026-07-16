<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;

class StoreSecurityIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'security';
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'flat_id' => ['nullable', 'exists:flats,id'],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }
}
