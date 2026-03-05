<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'beer_type_id' => ['required', 'integer', 'exists:beer_types,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'speed' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
