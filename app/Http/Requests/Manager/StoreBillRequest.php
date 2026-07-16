<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'manager';
    }

    public function rules(): array
    {
        return [
            'target_flat_id' => ['nullable', 'exists:flats,id'],
            'category' => ['required', 'string', 'max:100'],
            'period' => ['required', 'date_format:Y-m'],
            'due_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
